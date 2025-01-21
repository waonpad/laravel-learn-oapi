<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;
use OpenApi\SchemaDefinitions\Responses\InternalServerError;
use OpenApi\SchemaDefinitions\Responses\Unauthorized;

class LogoutController extends Controller
{
    #[OA\Post(
        operationId: 'logout',
        path: '/logout',
        tags: ['Auth'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 204,
                description: '',
            ),
            new OA\Response(response: 401, ref: Unauthorized::class),
            new OA\Response(response: 500, ref: InternalServerError::class),
        ]
    )]
    public function __invoke(Request $request): Response
    {
        $request->user()->currentAccessToken()->delete();

        return response()->noContent();
    }
}
