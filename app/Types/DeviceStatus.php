<?php

namespace App\Types;

class DeviceStatus
{
    const DISABLED = -1;
    const STANDBY = 0;
    const IN_MOTORWAY = 1;

    /**
     * @param int $status
     * @return string
     */
    public static function mapToText(int $status): string
    {
        return match ($status) {
            self::DISABLED => 'DISABLED',
            self::STANDBY => 'STANDBY',
            self::IN_MOTORWAY => 'IN_MOTORWAY',
            default => 'NOT FOUND',
        };
    }
}
