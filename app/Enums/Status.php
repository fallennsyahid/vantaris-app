<?php

namespace App\Enums;

enum Status: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';

    public static function getAllStatuses(): array
    {
        return array_column(self::cases(), 'value');
    }
}
