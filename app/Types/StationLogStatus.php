<?php

namespace App\Types;

class StationLogStatus
{
    const PROCESSED = 1;
    const NOT_PROCESSED = 0;

    /**
     * @param int $status
     * @return string
     */
    public static function mapToText(int $status) {
        return match($status) {
            self::NOT_PROCESSED => 'NOT PROCESSED',
            self::PROCESSED => 'PROCESSED'
        };
    }
}
