<?php

declare(strict_types=1);

namespace App\Http\DTO\Auth;

class LoginDto
{
    public readonly string $email;

    public readonly string $password;

    /**
     * @param array{email: string, password: string} $input
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
     * @return array{email: string, password: string}
     */
    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'password' => $this->password,
        ];
    }
}
