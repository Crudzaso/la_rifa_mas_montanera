<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        // Verificar resultados de lotería diariamente
        $schedule->command('lottery:check-winners')
                    ->between('16:00', '16:09')
                    ->dailyAt('16:07');

        // Actualizar estados de rifas (después de verificar ganadores)
        $schedule->command('raffles:update-status')->dailyAt('16:10');
    }
}
