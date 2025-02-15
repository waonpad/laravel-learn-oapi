<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\RegisterRequestBodyValidationError;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use OpenApi\SchemaDefinitions\Responses\InternalServerError;

class RegisterController extends Controller
{
    #[OA\Post(
        operationId: 'register',
        path: '/register',
        tags: ['Auth'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: RegisterRequest::class)
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
                content: new OA\JsonContent(ref: RegisterRequestBodyValidationError::class)
            ),
            new OA\Response(response: 500, ref: InternalServerError::class),
        ]
    )]
    public function __invoke(RegisterRequest $request): JsonResponse
    {
        $input = $request->makeInput();

        $user = User::create([
            'name' => $input->name,
            'email' => $input->email,
            'password' => $input->password,
        ]);

        $token = $user->createToken('AccessToken')->plainTextToken;

        return new JsonResponse(['token' => $token], 200);
    }
}
