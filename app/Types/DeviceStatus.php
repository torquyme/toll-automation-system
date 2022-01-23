<?php

namespace App\Types;

class DeviceStatus
{
    const ACTIVE = 1;
    const NOT_ACTIVE = 0;

    /**
     * @param int $status
     * @return string
     */
    public static function mapToText(int $status): string
    {
        return match ($status) {
            self::ACTIVE => 'ACTIVE',
            self::NOT_ACTIVE => 'NOT ACTIVE',
            default => 'NOT FOUND',
        };
    }
}
