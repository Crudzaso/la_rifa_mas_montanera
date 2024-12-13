<?php

namespace App\Services;

use App\Helpers\EmailHelperGlobal;
use App\Models\Raffle;
use App\Models\Tickets;
use Illuminate\Database\Eloquent\Collection;

class RaffleService
{

    private $emailHelperGlobal;

    public function __construct(EmailHelperGlobal $emailHelperGlobal)
    {
        $this->emailHelperGlobal = $emailHelperGlobal;
    }

    public function updateWinningTickets(string $winningNumber, Raffle $raffle): void
    {
        $tickets = Tickets::where('raffle_id', $raffle->id)
                        ->where('ticket_number', $winningNumber)
                        ->get();

        foreach ($tickets as $ticket) {
            $ticket->update(['is_winner' => true]);

            $user = $ticket->user;
            if ($user) {
                $prize = $raffle->prize; 
                $this->emailHelperGlobal->sendWinnerEmail($user, $raffle, $prize);
            }
        }
    }


    public function getTodayEndingRaffles(): Collection
    {
        return Raffle::where('end_date', now()->format('Y-m-d'))
                    ->where('status', 'ongoing')
                    ->get();
    }

    public function finalizeRaffle(Raffle $raffle): void
    {
        $raffle->update(['status' => 'finished']);
    }
}