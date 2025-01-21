<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireJson
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(Request): (Response) $next
     */
    public function handle(Request $request, \Closure $next): Response
    {
        // クライアントからのAccept要求（クライアントがResponseで欲しいデータ形式）は全てJSONとする。
        // これが無いとAuthenticationExceptionを勝手に捕まえてloginルートにリダイレクトしようとしたりしてしまう
        $request->headers->set('Accept', 'application/json');

        return $next($request);
    }
}
