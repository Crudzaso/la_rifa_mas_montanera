<?php

namespace App\Console\Commands;

use App\Models\Raffle;
use Illuminate\Console\Command;
use Carbon\Carbon;

class UpdateRaffleStatus extends Command
{
    protected $signature = 'raffles:update-status';
    protected $description = 'Actualiza automáticamente el estado de las rifas según la fecha';

    public function handle()
    {
        $this->info('Iniciando actualización de estados de rifas...');

        Raffle::chunk(100, function ($raffles) {
            foreach ($raffles as $raffle) {
                $now = now();
                $startDate = Carbon::parse($raffle->start_date);
                $endDate = Carbon::parse($raffle->end_date);

                // Determinar estado según fechas
                $newStatus = null;

                if ($now->lt($startDate)) {
                    $newStatus = 'pending';
                } elseif ($now->between($startDate, $endDate)) {
                    $newStatus = 'ongoing';
                } elseif ($now->gt($endDate)) {
                    $newStatus = 'finished';
                }

                // Actualizar solo si el estado ha cambiado
                if ($newStatus && $raffle->status !== $newStatus) {
                    $raffle->update(['status' => $newStatus]);
                    $this->line("Rifa ID {$raffle->id} actualizada a {$newStatus}");
                }
            }
        });

        $this->info('Actualización completada!');
    }
}
