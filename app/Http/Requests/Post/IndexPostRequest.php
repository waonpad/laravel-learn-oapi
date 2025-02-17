<?php

declare(strict_types=1);

namespace App\Http\Requests\Post;

use App\Http\Requests\CustomFormRequest;
use OpenApi\Attributes as OA;

class IndexPostRequest extends CustomFormRequest
{
    public function rules(): array
    {
        return [
            'query.page' => ['present', 'integer', 'min:1'],
        ];
    }

    public function validationData(): array
    {
        return array_merge(parent::validationData(), [
            'query' => [
                'page' => $this->query('page', '1'),
            ],
        ]);
    }
}

#[OA\ValidationErrorSchema(
    validationErrorProperties: ['query.page'],
)]
class IndexPostRequestQueryValidationError {}
