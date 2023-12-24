<?php

namespace App\Enums;

trait EnumMethods
{
    public static function toArray(): array
    {
        return array_combine(self::keys(), self::values());
    }

    public static function keys(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
