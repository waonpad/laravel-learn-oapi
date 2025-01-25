<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Exceptions\AuthenticationRequiredException;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;
use OpenApi\SchemaDefinitions\Responses\InternalServerError;
use OpenApi\SchemaDefinitions\Responses\Unauthorized;

class ShowAuthUserController extends Controller
{
    #[OA\Get(
        operationId: 'getAuthUser',
        path: '/me',
        tags: ['Auth'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: '',
                content: new OA\JsonContent(ref: UserResource::class)
            ),
            new OA\Response(response: 401, ref: Unauthorized::class),
            new OA\Response(response: 500, ref: InternalServerError::class),
        ]
    )]
    public function __invoke(Request $request): UserResource
    {
        /** @var null|User $user */
        $user = Auth::user();

        if ($user === null) {
            throw new AuthenticationRequiredException();
        }

        return new UserResource($user);
    }
}
