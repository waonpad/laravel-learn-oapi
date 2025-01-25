<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Exceptions\AuthenticationRequiredException;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
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
        /** @var null|User $user */
        $user = Auth::user();

        if ($user === null) {
            throw new AuthenticationRequiredException();
        }

        /** @var PersonalAccessToken $accessToken */
        $accessToken = $user->currentAccessToken();

        $accessToken->delete();

        return response()->noContent();
    }
}
