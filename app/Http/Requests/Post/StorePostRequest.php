<?php

declare(strict_types=1);

namespace App\Http\Requests\Post;

use App\Exceptions\AuthenticationRequiredException;
use App\Http\DTO\Post\StorePostDto;
use App\Http\Requests\CustomFormRequest;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(
            property: 'content',
            type: 'string',
        ),
    ],
    required: ['content'],
)]
class StorePostRequest extends CustomFormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('create', [Post::class]);
    }

    public function rules(): array
    {
        return [
            'content' => ['required', 'string', 'max:255'],
        ];
    }

    public function makeInput(): StorePostDto
    {
        /** @var null|User $user */
        $user = Auth::user();

        if ($user === null) {
            throw new AuthenticationRequiredException();
        }

        $validated = $this->validated();

        return new StorePostDto([
            'content' => $validated['content'],
            'user_id' => $user->id,
        ]);
    }
}

#[OA\ValidationErrorSchema(
    validationErrorProperties: ['content'],
)]
class StorePostRequestBodyValidationError {}
