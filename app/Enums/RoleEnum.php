<?php

namespace App\Enums;

enum RoleEnum: string
{
    case DEVELOPER = 'developer';
    case SUPERADMIN = 'superadmin';
    case ADMIN = 'admin';
    case CAHSIER = 'cashier';
    case STUDENT = 'student';
    case PARENT = 'parent';

    public static function getID(string $role): int
    {
        return match ($role) {
            self::DEVELOPER => 1,
            self::SUPERADMIN => 2,
            self::ADMIN => 3,
            self::CAHSIER => 4,
            self::STUDENT => 5,
            self::PARENT => 6
        };
    }
}
