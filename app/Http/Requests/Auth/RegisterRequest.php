<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use App\Http\DTO\Auth\RegisterDto;
use App\Http\Requests\CustomFormRequest;
use OpenApi\Attributes as OA;

/**
 * @phpstan-type ValidatedRegisterRequest=array{name: string, email: string, password: string}
 */
#[OA\Schema(
    properties: [
        new OA\Property(
            property: 'name',
            type: 'string',
            example: 'Test User',
        ),
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
        new OA\Property(
            property: 'password_confirmation',
            type: 'string',
            example: 'password',
        ),
    ],
    required: ['name', 'email', 'password', 'password_confirmation'],
)]
class RegisterRequest extends CustomFormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', 'min:8'],
        ];
    }

    public function makeInput(): RegisterDto
    {
        /** @var ValidatedRegisterRequest $validated */
        $validated = $this->validated();

        return new RegisterDto([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);
    }
}

#[OA\ValidationErrorSchema(
    validationErrorProperties: ['name', 'email', 'password'],
)]
class RegisterRequestBodyValidationError {}
