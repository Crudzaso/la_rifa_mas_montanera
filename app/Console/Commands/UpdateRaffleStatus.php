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
        $now = Carbon::now()->startOfDay();

        Raffle::chunk(100, function ($raffles) use ($now) {
            foreach ($raffles as $raffle) {
                $startDate = Carbon::parse($raffle->start_date)->startOfDay();
                $endDate = Carbon::parse($raffle->end_date)->startOfDay();

                $newStatus = null;

                if ($now->lt($startDate)) {
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
}
