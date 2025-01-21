<?php

declare(strict_types=1);

namespace App\Http\Requests\Post;

use App\Http\Requests\CustomFormRequest;
use App\Models\Post;
use Illuminate\Support\Facades\Gate;
use OpenApi\Attributes as OA;

class DestroyPostRequest extends CustomFormRequest
{
    public function authorize(): bool
    {
        $post = Post::findOrFail($this->route('id'));

        return Gate::allows('delete', [Post::class, $post]);
    }

    public function rules(): array
    {
        return [
            'path' => ['required', 'array'],
            'path.id' => ['required', 'string', 'min:1'],
        ];
    }

    public function validationData(): array
    {
        $data = $this->all();
        $data['path'] = [
            'id' => $this->route('id'),
        ];

        return $data;
    }
}

#[OA\ValidationErrorSchema(
    validationErrorProperties: ['path.id'],
)]
class DestroyPostRequestPathValidationError {}
