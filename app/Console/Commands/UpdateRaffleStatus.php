<?php

namespace App\Console\Commands;

use App\Models\Raffle;
use Illuminate\Console\Command;
use Carbon\Carbon;

class UpdateRaffleStatus extends Command
{
    protected $signature = 'raffles:update-status';
    protected $description = 'Actualiza el estado de las rifas según sus fechas';

    public function handle()
    {
        $now = Carbon::now();

        // Finalizar rifas que jugaban hoy
        Raffle::where('end_date', $now->format('Y-m-d'))
            ->where('status', 'ongoing')
            ->chunk(100, function ($raffles) {
                foreach ($raffles as $raffle) {
                    $raffle->update(['status' => 'finished']);
                    $this->info("Rifa ID {$raffle->id} finalizada");
                }
            });

        if ($now->format('H:i') === '16:07') {
            $this->checkLotteryResults();
        }

        Raffle::chunk(100, function ($raffles) use ($now) {
            foreach ($raffles as $raffle) {
                $startDate = Carbon::parse($raffle->start_date)->startOfDay();
                $endDate = Carbon::parse($raffle->end_date)->startOfDay();

                $newStatus = null;

                if ($raffle->tickets()->where('is_winner', true)->exists()) {
                    $newStatus = 'finished';
                } elseif ($now->lt($startDate)) {
                    $newStatus = 'pending';
                } elseif ($now->between($startDate, $endDate)) {
                    $newStatus = 'ongoing';
                } elseif ($now->gt($endDate)) {
                    $newStatus = 'finished';
                }

                if ($newStatus && $raffle->status !== $newStatus) {
                    $raffle->update(['status' => $newStatus]);
                    $this->info("Rifa ID {$raffle->id}: {$newStatus}");
                }
            }
        });
    }

    private function checkLotteryResults()
    {
        $this->call('lottery:check-winners');
    }
}
