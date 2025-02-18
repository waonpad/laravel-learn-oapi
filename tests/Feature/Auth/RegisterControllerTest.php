<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Http\Controllers\Auth\RegisterController;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use Tests\TestCase;

/**
 * @internal
 */
#[CoversClass(RegisterController::class)]
#[CoversMethod(RegisterController::class, '__invoke')]
final class RegisterControllerTest extends TestCase
{
    public function testDBにユーザーとアクセストークンが作成される(): void
    {
        $name = Str::random();
        $email = Str::random().'@example.com';
        $password = Str::random();

        // ユーザーとアクセストークンの作成,更新日時を固定
        Carbon::setTestNow(Carbon::now());

        $response = $this->postJson('/register', [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'passwordConfirmation' => $password,
        ]);

        $createdUser = User::where('email', $email)->firstOrFail();

        $response->assertStatus(200);
        $this->assertDatabaseHas(User::class, [
            'name' => $name,
            'email' => $email,
            'email_verified_at' => null,
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);
        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_type' => User::class,
            'tokenable_id' => $createdUser->id,
            'name' => 'AccessToken',
            'abilities' => '["*"]',
            'last_used_at' => null,
            'expires_at' => null,
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);
    }

    public function testアクセストークンが返却される(): void
    {
        $name = Str::random();
        $email = Str::random().'@example.com';
        $password = Str::random();

        $response = $this->postJson('/register', [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'passwordConfirmation' => $password,
        ]);

        $response->assertStatus(200);
        $response->assertJson(
            fn (AssertableJson $json): AssertableJson => $json->where(
                // アクセストークンが | で区切られた文字列が返却されることを確認
                'token',
                fn ($token): bool => is_string($token) && count(explode('|', $token)) === 2
            )
        );
    }

    public function test入力したパスワードがハッシュ化されてDBに保存される(): void
    {
        $name = Str::random();
        $email = Str::random().'@example.com';
        $password = Str::random();

        $this->postJson('/register', [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'passwordConfirmation' => $password,
        ]);

        $user = User::where('email', $email)->firstOrFail();

        $savedPassword = $user->password;

        $this->assertTrue(
            Hash::check($password, $savedPassword),
            'パスワードがハッシュ化されてDBに保存されていれば、Hash::check() は true を返す'
        );
    }

    public function testパスワードが確認用と一致しない場合、バリデーションエラー(): void
    {
        $name = Str::random();
        $email = Str::random().'@example.com';
        $password = Str::random();
        $invalidPassword = "invalid{$password}";

        $response = $this->postJson('/register', [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'passwordConfirmation' => $invalidPassword,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    }

    public function test確認用パスワードが存在しない場合、バリデーションエラー(): void
    {
        $name = Str::random();
        $email = Str::random().'@example.com';
        $password = Str::random();

        $response = $this->postJson('/register', [
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    }

    public function testメールアドレスの形式が正しくない場合、バリデーションエラー(): void
    {
        $name = Str::random();
        $email = Str::random();
        $password = Str::random();

        $response = $this->postJson('/register', [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'passwordConfirmation' => $password,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    public function test既に存在するメールアドレスを指定した場合、バリデーションエラー(): void
    {
        $name = Str::random();
        $email = Str::random().'@example.com';
        $password = Str::random();

        User::factory()->create([
            'email' => $email,
        ]);

        $response = $this->postJson('/register', [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'passwordConfirmation' => $password,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }
}
