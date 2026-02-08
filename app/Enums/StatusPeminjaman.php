<?php

namespace App\Enums;

enum StatusPeminjaman: string
{
    case PENDING = 'pending';
    case DISETUJUI = 'disetujui';
    case DITOLAK = 'ditolak';
    case DIAMBIL = 'diambil';
    case KEMBALI = 'kembali';
    case TERLAMBAT = 'terlambat';

    public static function getAllStatuses(): array
    {
        return array_column(self::cases(), 'value');
    }
}
