<?php
// app/Console/Kernel.php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        // Ejecutar cada hora
        $schedule->command('raffles:update-status')
                ->hourly()
                ->appendOutputTo(storage_path('logs/raffles-status.log'));
    }
}
