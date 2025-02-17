<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Http\Controllers\Auth\LoginController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use Tests\TestCase;

/**
 * @internal
 */
#[CoversClass(LoginController::class)]
#[CoversMethod(LoginController::class, '__invoke')]
final class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testDBにアクセストークンが作成される(): void
    {
        $password = Str::random();

        $user = User::factory()->create([
            'password' => $password,
        ]);

        // アクセストークンの作成,更新日時を固定
        Carbon::setTestNow(Carbon::now());

        $response = $this->postJson('/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_type' => User::class,
            'tokenable_id' => $user->id,
            'name' => 'AccessToken',
            'abilities' => '["*"]',
            'expires_at' => null,
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);
    }

    public function testアクセストークンが返却される(): void
    {
        $password = Str::random();

        $user = User::factory()->create([
            'password' => $password,
        ]);

        $response = $this->postJson('/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(200);
        $response->assertJson(
            fn (AssertableJson $json): AssertableJson => $json
                ->where(
                    'token',
                    fn ($token): bool => is_string($token) && count(explode('|', $token)) === 2
                )
        );
    }

    public function testメールアドレスが一致するユーザーが存在しない場合は401エラーが返却される(): void
    {
        $password = Str::random();

        $response = $this->postJson('/login', [
            'email' => Str::random().'@example.com',
            'password' => $password,
        ]);

        $this->assertJsonCommonErrorResponse($response, 401);
    }

    public function test無効なパスワードの場合は401エラーが返却される(): void
    {
        // ユーザーのパスワードは必ずハッシュ化されているため、Str::random() で生成した文字列は必ず無効なパスワードとなる
        $invalidPassword = Str::random();

        $user = User::factory()->create();

        $response = $this->postJson('/login', [
            'email' => $user->email,
            'password' => $invalidPassword,
        ]);

        $this->assertJsonCommonErrorResponse($response, 401);
    }
}
