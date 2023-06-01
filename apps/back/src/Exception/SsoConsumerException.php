<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;
use OneLogin\Saml2\Auth;
use Throwable;

final class SsoConsumerException extends Exception
{
    public function __construct(Auth $auth, int $code = 0, Throwable|null $previous = null)
    {
        $message = '[SSOService] - Login response has error. Reason: ' . $auth->getLastErrorReason() . '. Errors: '
            . implode(', ', $auth->getErrors());
        parent::__construct($message, $code, $previous);
    }
}
