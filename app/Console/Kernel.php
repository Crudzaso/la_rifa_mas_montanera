<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        // Verificar resultados de lotería a las 4:07 PM
        $schedule->command('lottery:check-winners')->dailyAt('16:07');

        // Actualizar estados de rifas a las 4:10 PM (después de verificar ganadores)
        $schedule->command('raffles:update-status')->dailyAt('16:10');
    }
}
