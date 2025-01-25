<?php

declare(strict_types=1);

namespace App\Exceptions;

/**
 * 認証されていないユーザーが処理の実行を試みた場合に投げる例外.
 */
class AuthenticationRequiredException extends \LogicException
{
    /** @var string */
    protected $message = '認証されたユーザーのみが処理を実行できるようにしなければなりません';
}
