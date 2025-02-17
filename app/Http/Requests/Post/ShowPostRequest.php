<?php

declare(strict_types=1);

namespace App\Http\Requests\Post;

use App\Http\Requests\CustomFormRequest;
use OpenApi\Attributes as OA;

class ShowPostRequest extends CustomFormRequest
{
    public function rules(): array
    {
        return [
            'path.id' => ['present', 'string', 'min:1'],
        ];
    }

    public function validationData(): array
    {
        return array_merge(parent::validationData(), [
            'path' => [
                'id' => $this->route('id'),
            ],
        ]);
    }
}

#[OA\ValidationErrorSchema(
    validationErrorProperties: ['path.id'],
)]
class ShowPostRequestPathValidationError {}
