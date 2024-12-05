<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('raffles:update-status')
                ->daily()
                ->at('00:01')
                ->appendOutputTo(storage_path('logs/raffles-status.log'));

        $schedule->command('lottery:check-winners')
                ->dailyAt('16:07')
                ->timezone('America/Bogota');
                
    }
}
