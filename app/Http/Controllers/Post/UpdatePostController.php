<?php

declare(strict_types=1);

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Http\Requests\Post\UpdatePostRequestBodyValidationError;
use App\Http\Requests\Post\UpdatePostRequestPathValidationError;
use App\Http\Resources\PostResource;
use App\UseCases\Post\UpdatePostAction;
use OpenApi\Attributes as OA;
use OpenApi\SchemaDefinitions\PathParameters\PostId;
use OpenApi\SchemaDefinitions\Responses\Forbidden;
use OpenApi\SchemaDefinitions\Responses\InternalServerError;
use OpenApi\SchemaDefinitions\Responses\Unauthorized;

class UpdatePostController extends Controller
{
    /**
     * Update the specified resource in storage.
     */
    #[OA\Patch(
        operationId: 'updatePost',
        path: '/posts/{id}',
        tags: ['Post'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\PathParameter(ref: PostId::REF),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: UpdatePostRequest::class),
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: '',
                content: new OA\JsonContent(ref: PostResource::class),
            ),
            new OA\Response(
                response: 400,
                description: '',
                content: new OA\JsonContent(ref: UpdatePostRequestPathValidationError::class),
            ),
            new OA\Response(
                response: 422,
                description: '',
                content: new OA\JsonContent(ref: UpdatePostRequestBodyValidationError::class),
            ),
            new OA\Response(response: 401, ref: Unauthorized::class),
            new OA\Response(response: 403, ref: Forbidden::class),
            new OA\Response(response: 500, ref: InternalServerError::class),
        ],
    )]
    public function __invoke(UpdatePostRequest $request, string $id, UpdatePostAction $action): PostResource
    {
        $input = $request->makeInput();

        $stored = $action($id, $input);

        return new PostResource($stored);
    }
}
