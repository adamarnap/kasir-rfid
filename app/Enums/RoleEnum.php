<?php

namespace App\Enums;

enum RoleEnum: string
{
    case DEVELOPER = 'developer';
    case SUPERADMIN = 'superadmin';
    case ADMIN = 'admin';
    case USER = 'user';

    public static function getID(string $role): int
    {
        return match ($role) {
            self::DEVELOPER => 1,
            self::SUPERADMIN => 2,
            self::ADMIN => 3,
            self::USER => 4,
        };
    }
}
