<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Http\Controllers\Auth\RegisterController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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
    use RefreshDatabase;

    public function testユーザーが作成され、ステータスコードが200(): void
    {
        $name = Str::random();
        $email = Str::random().'@example.com';
        $password = Str::random(8);

        // ユーザーの作成,更新日時を固定
        Carbon::setTestNow(Carbon::now());

        $response = $this->postJson('/register', [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'passwordConfirmation' => $password,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas(User::class, [
            'name' => $name,
            'email' => $email,
            'email_verified_at' => null,
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);
    }

    public function testユーザーが作成され、入力したパスワードがハッシュ化されてDBに保存される(): void
    {
        $name = Str::random();
        $email = Str::random().'@example.com';
        $password = Str::random(8);

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

    public function testパスワードが確認用と一致しない場合、ユーザーが作成されずステータスコードが422(): void
    {
        $name = Str::random();
        $email = Str::random().'@example.com';

        $passwordLength = 8;

        $password = Str::random($passwordLength);
        $invalidPassword = Str::random($passwordLength + 1);

        $response = $this->postJson('/register', [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'passwordConfirmation' => $invalidPassword,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
        $this->assertDatabaseEmpty(User::class);
    }
}
