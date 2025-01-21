<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UnescapeJsonResponse
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(Request): (Response) $next
     */
    public function handle(Request $request, \Closure $next): Response
    {
        $response = $next($request);

        // JSONでない場合はそのまま
        if (!$response instanceof JsonResponse) {
            return $response;
        }

        // Unicodeエスケープさせないようにオプションを追加
        $response->setEncodingOptions($response->getEncodingOptions() | JSON_UNESCAPED_UNICODE);

        return $response;
    }
}
