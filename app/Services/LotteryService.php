<?php
// app/Services/LotteryService.php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class LotteryService
{
    public function fetchResults(): array
    {
        $url = 'https://api-resultadosloterias.com/api/results';
        $response = Http::withOptions(['verify' => false])->get($url);

        if (!$response->successful()) {
            throw new \Exception('Error al obtener resultados', $response->status());
        }

        return $response->json()['data'] ?? [];
    }

    public function getAntioqueñitaTarde(array $results): ?array
    {
        return collect($results)->firstWhere('lottery', 'ANTIOQUEÑITA TARDE');
    }

    public function getLastTwoDigits(string $result): string
    {
        return substr($result, -2);
    }
}