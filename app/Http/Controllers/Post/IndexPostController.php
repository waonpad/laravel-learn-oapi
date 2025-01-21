<?php

declare(strict_types=1);

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\IndexPostRequest;
use App\Http\Requests\Post\IndexPostRequestQueryValidationError;
use App\Http\Resources\PostCollection;
use App\UseCases\Post\IndexPostAction;
use OpenApi\Attributes as OA;
use OpenApi\SchemaDefinitions\QueryParameters\Page;
use OpenApi\SchemaDefinitions\Responses\InternalServerError;

class IndexPostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    #[OA\Get(
        operationId: 'getPosts',
        path: '/posts',
        tags: ['Post'],
        parameters: [
            new OA\QueryParameter(ref: Page::REF),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: '',
                content: new OA\JsonContent(ref: PostCollection::class),
            ),
            new OA\Response(
                response: 400,
                description: '',
                content: new OA\JsonContent(ref: IndexPostRequestQueryValidationError::class)
            ),
            new OA\Response(response: 500, ref: InternalServerError::class),
        ],
    )]
    public function __invoke(IndexPostRequest $request, IndexPostAction $action): PostCollection
    {
        return new PostCollection($action());
    }
}
