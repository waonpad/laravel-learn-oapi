<?php

declare(strict_types=1);

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\DestroyPostRequest;
use App\Http\Requests\Post\DestroyPostRequestPathValidationError;
use App\UseCases\Post\DestroyPostAction;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;
use OpenApi\SchemaDefinitions\PathParameters\PostId;
use OpenApi\SchemaDefinitions\Responses\Forbidden;
use OpenApi\SchemaDefinitions\Responses\InternalServerError;
use OpenApi\SchemaDefinitions\Responses\Unauthorized;

class DestroyPostController extends Controller
{
    /**
     * Remove the specified resource from storage.
     */
    #[OA\Delete(
        operationId: 'deletePost',
        path: '/posts/{id}',
        tags: ['Post'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\PathParameter(ref: PostId::REF),
        ],
        responses: [
            new OA\Response(response: 204, description: ''),
            new OA\Response(
                response: 400,
                description: '',
                content: new OA\JsonContent(ref: DestroyPostRequestPathValidationError::class),
            ),
            new OA\Response(response: 401, ref: Unauthorized::class),
            new OA\Response(response: 403, ref: Forbidden::class),
            new OA\Response(response: 500, ref: InternalServerError::class),
        ],
    )]
    public function __invoke(DestroyPostRequest $request, string $id, DestroyPostAction $action): Response
    {
        $action($id);

        return response()->noContent();
    }
}
