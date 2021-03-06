<?php
/*
 * This file has been automatically generated by TDBM.
 * You can edit this file as it will not be overwritten.
 */

declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\Model\Generated\BaseResetPasswordToken;
use TheCodingMachine\GraphQLite\Annotations\SourceField;
use TheCodingMachine\GraphQLite\Annotations\Type;

use function Safe\password_hash;

use const PASSWORD_DEFAULT;

/**
 * The ResetPasswordToken class maps the 'reset_password_tokens' table in database.
 *
 * @Type
 * @SourceField(name="id", outputType="ID")
 */
class ResetPasswordToken extends BaseResetPasswordToken
{
    public function setToken(string $token): void
    {
        parent::setToken(password_hash($token, PASSWORD_DEFAULT));
    }
}
