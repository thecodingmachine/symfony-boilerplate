<?php

declare(strict_types=1);

namespace App\Exception\Authenticator;

use Exception;
use OneLogin\Saml2\Auth;
use Throwable;

class SsoConsumerException extends Exception
{
    public function __construct(Auth $auth, int $code = 0, Throwable|null $previous = null)
    {
        $message = '[SSOService] - Login response has error. Reason: ' . $auth->getLastErrorReason() . '. Errors: '
            . implode(', ', $auth->getErrors());

        parent::__construct($message, $code, $previous);
    }
}
