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

/**
 * @phpstan-type ValidatedStorePostRequest=array{content: string}
 * @phpstan-type StorePostInput=array{content: string, user_id: int}
 */
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
            'content' => ['present', 'string', 'between:1,255'],
        ];
    }

    public function makeInput(): StorePostDto
    {
        /** @var null|User $user */
        $user = Auth::user();

        if ($user === null) {
            throw new AuthenticationRequiredException();
        }

        /** @var ValidatedStorePostRequest $validated */
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
