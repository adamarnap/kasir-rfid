<?php

namespace App\Enums;

enum GenderEnum: string
{
    case LAKI_LAKI = 'l';
    case PEREMPUAN = 'p';
    
    public function label(): string
    {
        return match($this) {
            self::LAKI_LAKI => 'Laki-laki',
            self::PEREMPUAN => 'Perempuan',
        };
    }
}
