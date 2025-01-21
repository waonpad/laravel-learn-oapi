<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\LoginRequestBodyValidationError;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;
use OpenApi\SchemaDefinitions\Responses\InternalServerError;
use OpenApi\SchemaDefinitions\Responses\Unauthorized;

class LoginController extends Controller
{
    #[OA\Post(
        operationId: 'login',
        path: '/login',
        tags: ['Auth'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: LoginRequest::class)
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: '',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'token',
                            type: 'string',
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: '',
                content: new OA\JsonContent(ref: LoginRequestBodyValidationError::class)
            ),
            new OA\Response(response: 401, ref: Unauthorized::class),
            new OA\Response(response: 500, ref: InternalServerError::class),
        ]
    )]
    public function __invoke(LoginRequest $request): JsonResponse
    {
        $input = $request->makeInput();

        $credentials = [
            'email' => $input->email,
            'password' => $input->password,
        ];

        if (Auth::attempt($credentials)) {
            $user = $request->user();
            $token = $user->createToken('AccessToken')->plainTextToken;

            return new JsonResponse(['token' => $token], 200);
        }

        throw new AuthenticationException();
    }
}
