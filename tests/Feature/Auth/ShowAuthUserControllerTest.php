<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Http\Controllers\Auth\ShowAuthUserController;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use Tests\TestCase;

/**
 * @internal
 */
#[CoversClass(ShowAuthUserController::class)]
#[CoversMethod(ShowAuthUserController::class, '__invoke')]
final class ShowAuthUserControllerTest extends TestCase
{
    public function testアクセストークンに紐つくユーザーが返却される(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('AccessToken')->plainTextToken;

        $response = $this->withHeaders(['Authorization' => "Bearer {$token}"])->getJson('/me');

        /** @var Carbon */
        $emailVerifiedAt = $user->email_verified_at;
        /** @var Carbon */
        $createdAt = $user->created_at;
        /** @var Carbon */
        $updatedAt = $user->updated_at;

        $response->assertStatus(200);
        $response->assertExactJson([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'emailVerifiedAt' => $emailVerifiedAt->toISOString(),
            'createdAt' => $createdAt->toISOString(),
            'updatedAt' => $updatedAt->toISOString(),
        ]);
    }

    public function testアクセストークンの最終使用日時が更新される(): void
    {
        $testLastUsedAt = Carbon::now();
        Carbon::setTestNow($testLastUsedAt);

        $user = User::factory()->create();
        $token = $user->createToken('AccessToken')->plainTextToken;

        $response = $this->withHeaders(['Authorization' => "Bearer {$token}"])->getJson('/me');

        $response->assertStatus(200);
        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_type' => User::class,
            'tokenable_id' => $user->id,
            'name' => 'AccessToken',
            'abilities' => '["*"]',
            'last_used_at' => $testLastUsedAt->toDateTimeString(),
            'expires_at' => null,
        ]);
    }

    public function testアクセストークンが存在しない場合、認証エラー(): void
    {
        $response = $this->getJson('/me');

        $this->assertJsonCommonErrorResponse($response, 401);
    }

    public function testアクセストークンが不正な場合、認証エラー(): void
    {
        $user = User::factory()->create();
        // 正常なトークンを作成
        $user->createToken('AccessToken');

        // 不正なトークンを作成
        $invalidToken = Str::random();

        $response = $this->withHeaders(['Authorization' => "Bearer {$invalidToken}"])->getJson('/me');

        $this->assertJsonCommonErrorResponse($response, 401);
    }
}
