<?php

namespace App\Enums;

enum ParentRelationshipEnum: string
{
    case FATHER = 'father';
    case MOTHER = 'mother';
    case GUARDIAN = 'guardian';
    
    public function label(): string
    {
        return match($this) {
            self::FATHER => 'Ayah',
            self::MOTHER => 'Ibu',
            self::GUARDIAN => 'Wali',
        };
    }
}
