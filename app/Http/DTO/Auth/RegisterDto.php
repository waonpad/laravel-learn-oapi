<?php

declare(strict_types=1);

namespace App\Http\DTO\Auth;

class RegisterDto
{
    public readonly string $name;

    public readonly string $email;

    public readonly string $password;

    /**
     * @param array{name: string, email: string, password: string} $input
     */
    public function __construct(array $input)
    {
        // @phpstan-ignore isset.offset
        if (!isset($input['name'])) {
            throw new \InvalidArgumentException('name is required');
        }

        // @phpstan-ignore isset.offset
        if (!isset($input['email'])) {
            throw new \InvalidArgumentException('email is required');
        }

        // @phpstan-ignore isset.offset
        if (!isset($input['password'])) {
            throw new \InvalidArgumentException('password is required');
        }

        $this->name = $input['name'];
        $this->email = $input['email'];
        $this->password = $input['password'];
    }

    /**
     * @return array{name: string, email: string, password: string}
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
        ];
    }
}
