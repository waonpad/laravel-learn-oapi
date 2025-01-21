<?php

declare(strict_types=1);

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\ShowPostRequest;
use App\Http\Requests\Post\ShowPostRequestPathValidationError;
use App\Http\Resources\PostResource;
use App\UseCases\Post\ShowPostAction;
use OpenApi\Attributes as OA;
use OpenApi\SchemaDefinitions\PathParameters\PostId;
use OpenApi\SchemaDefinitions\Responses\InternalServerError;

class ShowPostController extends Controller
{
    /**
     * Display the specified resource.
     */
    #[OA\Get(
        operationId: 'getPost',
        path: '/posts/{id}',
        tags: ['Post'],
        parameters: [
            new OA\PathParameter(ref: PostId::REF),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: '',
                content: new OA\JsonContent(ref: PostResource::class),
            ),
            new OA\Response(
                response: 400,
                description: '',
                content: new OA\JsonContent(ref: ShowPostRequestPathValidationError::class),
            ),
            new OA\Response(response: 500, ref: InternalServerError::class),
        ],
    )]
    public function __invoke(ShowPostRequest $request, string $id, ShowPostAction $action): PostResource
    {
        $post = $action($id);

        return new PostResource($post);
    }
}
