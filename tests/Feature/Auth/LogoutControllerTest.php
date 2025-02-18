<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Http\Controllers\Auth\LogoutController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use Tests\TestCase;

/**
 * @internal
 */
#[CoversClass(LogoutController::class)]
#[CoversMethod(LogoutController::class, '__invoke')]
final class LogoutControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testアクセストークンが削除される(): void
    {
        $accessTokenTableName = 'personal_access_tokens';

        $user = User::factory()->create();
        $token = $user->createToken('AccessToken')->plainTextToken;

        // テーブル名をハードコードして、且つ削除される事を確認する都合
        // 念の為、まずアクセストークンが作成されていることを確認
        $this->assertDatabaseHas($accessTokenTableName, [
            'tokenable_type' => User::class,
            'tokenable_id' => $user->id,
            'name' => 'AccessToken',
        ]);

        $response = $this->withHeaders(['Authorization' => "Bearer {$token}"])->postJson('/logout');

        $response->assertStatus(204);
        $this->assertDatabaseEmpty($accessTokenTableName);
    }

    public function testアクセストークンが存在しない場合、認証エラー(): void
    {
        $response = $this->postJson('/logout');

        $this->assertJsonCommonErrorResponse($response, 401);
    }

    public function testアクセストークンが不正な場合、認証エラー(): void
    {
        $user = User::factory()->create();
        // 正常なアクセストークンを作成
        $user->createToken('AccessToken');

        // 不正なアクセストークンを作成
        $invalidToken = Str::random(40);

        $response = $this->withHeaders(['Authorization' => "Bearer {$invalidToken}"])->postJson('/logout');

        $this->assertJsonCommonErrorResponse($response, 401);
    }
}
