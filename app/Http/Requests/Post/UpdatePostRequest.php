<?php

declare(strict_types=1);

namespace App\Http\Requests\Post;

use App\Http\DTO\Post\UpdatePostDto;
use App\Http\Requests\CustomFormRequest;
use App\Models\Post;
use Illuminate\Support\Facades\Gate;
use OpenApi\Attributes as OA;

/**
 * @phpstan-type ValidatedUpdatePostRequest=array{content: string, path: array{id: non-empty-string}}
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
class UpdatePostRequest extends CustomFormRequest
{
    public function authorize(): bool
    {
        $post = Post::findOrFail($this->route('id'));

        return Gate::allows('update', [Post::class, $post]);
    }

    public function rules(): array
    {
        return [
            'content' => ['required', 'string', 'max:255'],
            'path' => ['required', 'array'],
            'path.id' => ['required', 'string', 'min:1'],
        ];
    }

    public function validationData(): array
    {
        return array_merge(parent::validationData(), [
            'path' => $this->route('id'),
        ]);
    }

    public function makeInput(): UpdatePostDto
    {
        /** @var ValidatedUpdatePostRequest $validated */
        $validated = $this->validated();

        return new UpdatePostDto([
            'content' => $validated['content'],
        ]);
    }
}

#[OA\ValidationErrorSchema(
    validationErrorProperties: ['content'],
)]
class UpdatePostRequestBodyValidationError {}

#[OA\ValidationErrorSchema(
    validationErrorProperties: ['path.id'],
)]
class UpdatePostRequestPathValidationError {}
