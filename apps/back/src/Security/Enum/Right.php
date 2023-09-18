<?php

declare(strict_types=1);

namespace App\Security\Enum;

enum Right: string
{
    case ROLE_RIGHT_ACCESS = 'ROLE_RIGHT_ACCESS';
    case ROLE_RIGHT_USER_READ = 'ROLE_RIGHT_USER_READ';
    case ROLE_RIGHT_USER_CREATE = 'ROLE_RIGHT_USER_CREATE';
    case ROLE_RIGHT_USER_DELETE = 'ROLE_RIGHT_USER_DELETE';
    case ROLE_RIGHT_USER_UPDATE = 'ROLE_RIGHT_USER_UPDATE';
}
