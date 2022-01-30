<?php

namespace App\Console;

use App\Console\Commands\GenerateMonthlyInvoices;
use App\Console\Commands\GenerateMonthlyUserInvoices;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        GenerateMonthlyUserInvoices::class,
        GenerateMonthlyInvoices::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command(GenerateMonthlyUserInvoices::class)->monthly();
    }
}
