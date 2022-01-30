<?php

namespace App\Types;

/**
 * StationLogAction
 */
class StationLogAction
{
    const ENTER = 0;
    const DRIVE_THROUGH = 1;
    const EXIT = 2;

    /**
     * @param int $action
     * @return string
     */
    public static function mapToText(int $action): string
    {
        return match ($action) {
            StationLogAction::ENTER => 'ENTER',
            StationLogAction::DRIVE_THROUGH => 'DRIVE THROUGH',
            StationLogAction::EXIT => 'EXIT'
        };
    }
}
