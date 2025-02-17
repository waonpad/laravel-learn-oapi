<?php

declare(strict_types=1);

namespace App\Http\DTO\Auth;

/**
 * @phpstan-import-type LoginInput from \App\Http\Requests\Auth\LoginRequest
 */
class LoginDto
{
    public readonly string $email;

    public readonly string $password;

    /**
     * @param LoginInput $input
     */
    public function __construct(array $input)
    {
        // @phpstan-ignore isset.offset
        if (!isset($input['email'])) {
            throw new \InvalidArgumentException('email is required');
        }

        // @phpstan-ignore isset.offset
        if (!isset($input['password'])) {
            throw new \InvalidArgumentException('password is required');
        }

        $this->email = $input['email'];
        $this->password = $input['password'];
    }

    /**
     * @return LoginInput
     */
    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'password' => $this->password,
        ];
    }
}
