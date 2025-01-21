<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use App\Http\DTO\Auth\LoginDto;
use App\Http\Requests\CustomFormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(
            property: 'email',
            type: 'string',
            example: 'test@example.com',
        ),
        new OA\Property(
            property: 'password',
            type: 'string',
            example: 'password',
        ),
    ],
    required: ['email', 'password'],
)]
class LoginRequest extends CustomFormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function makeInput(): LoginDto
    {
        $validated = $this->validated();

        return new LoginDto([
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);
    }
}

#[OA\ValidationErrorSchema(
    validationErrorProperties: ['email', 'password'],
)]
class LoginRequestBodyValidationError {}
