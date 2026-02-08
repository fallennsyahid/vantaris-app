<?php

namespace App\Enums;

enum RolesEnum: string
{
    case ADMIN = "admin";
    case PETUGAS = "petugas";
    case PEMINJAM = "peminjam";

    public static function getAllRoles(): array
    {
        return array_column(self::cases(), 'value');
    }
}
