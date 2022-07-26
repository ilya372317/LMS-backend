<?php

namespace App\Enum\User;

use App\Helper\EnumModifier\EnumArrayAbility;

enum UserRole: string
{
    use EnumArrayAbility;

    case BASE_USER = 'ROLE_USER';
    case ADMIN = 'ROLE_ADMIN';
    case SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    private static function getCases(): array
    {
        return self::cases();
    }
}