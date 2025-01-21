<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler
{
    public function handleExceptions(Exceptions $exceptions): void
    {
        // TODO: 例外ごとの処理を追加する
        // 例外自身にrenderメソッドを実装する事でここでハンドリングする必要が無くなる

        $exceptions->render(function (AuthenticationException $e, Request $request) {
            return new JsonResponse([
                'message' => $e->getMessage(),
            ], 401);
        });

        $exceptions->render(function (HttpException $e, Request $request) {
            // NotFoundHttpException, AccessDeniedHttpException, ...

            return new JsonResponse([
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        });

        // 処理されなかった例外はここで処理される
        $exceptions->render(function (\Throwable $e, Request $request) {
            return new JsonResponse([
                'message' => 'Internal Server Error',
            ], 500);
        });
    }
}
