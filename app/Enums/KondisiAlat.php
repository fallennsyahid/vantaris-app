<?php

namespace App\Enums;

enum KondisiAlat: string
{
    case BAIK = 'baik';
    case RUSAK = 'rusak';
    case HILANG = 'hilang';
    case TIDAK_LENGKAP = 'tidak_lengkap';

    public static function getAllKondisi(): array
    {
        return array_column(self::cases(), 'value');
    }
}
