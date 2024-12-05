<?php

namespace App\Http\Controllers\Lotteries;

use App\Models\LotteryResult;
use App\Services\LotteryService;
use App\Services\RaffleService;

class ProcessLotteryResults
{
    public function __construct(
        private LotteryService $lotteryService,
        private RaffleService $raffleService
    ) {}

    public function execute(): array
    {
        try {
            // Obtener resultados de lotería
            $results = $this->lotteryService->fetchResults();
            $antioquenitaTarde = $this->lotteryService->getAntioqueñitaTarde($results);

            if (!$antioquenitaTarde) {
                return ['data' => []];
            }

            // Obtener último número ganador
            $winningNumber = $this->lotteryService->getLastTwoDigits($antioquenitaTarde['result']);

            // Obtener rifas que terminan hoy
            $todayRaffles = $this->raffleService->getTodayEndingRaffles();

            foreach ($todayRaffles as $raffle) {
                // Actualizar tickets ganadores
                $this->raffleService->updateWinningTickets($winningNumber, $raffle);
                // Finalizar la rifa
                $this->raffleService->finalizeRaffle($raffle);
            }

            return ['data' => $results];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    private function saveLotteryResult(array $result): void
    {
        LotteryResult::updateOrCreate(
            [
                'slug' => $result['slug'],
                'date' => $result['date'],
            ],
            [
                'lottery' => $result['lottery'],
                'result' => $result['result'],
                'series' => $result['series'],
            ]
        );
    }

    
}
