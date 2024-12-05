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
        $results = $this->lotteryService->fetchResults();
        
        if (empty($results)) {
            return ['data' => [], 'message' => 'No hay resultados disponibles.'];
        }

        $antioqueñitaTarde = $this->lotteryService->getAntioqueñitaTarde($results);
        
        if ($antioqueñitaTarde) {
            $this->saveLotteryResult($antioqueñitaTarde);
            $this->processRaffles($antioqueñitaTarde['result']);
        }

        return ['data' => $results];
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

    private function processRaffles(string $result): void
    {
        $winningNumber = $this->lotteryService->getLastTwoDigits($result);
        $raffles = $this->raffleService->getTodayEndingRaffles();

        foreach ($raffles as $raffle) {
            $this->raffleService->updateWinningTickets($winningNumber, $raffle);
            $this->raffleService->finalizeRaffle($raffle);
        }
    }
}