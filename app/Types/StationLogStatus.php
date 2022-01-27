<?php

namespace App\Types;

class StationLogStatus
{
    const ENTER = 1;
    const EXIT = 2;

    public static function mapToText(int $status): string
    {
        return match($status) {
            self::ENTER => 'ENTER',
            self::EXIT => 'EXIT',
            default => 'NOT FOUND'
        };
    }
}
