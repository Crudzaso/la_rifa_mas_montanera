<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\LotteryResult;
use Illuminate\Support\Facades\Http;

class LotteryController extends Controller
{
    public function getResults()
    {
        $url = 'https://api-resultadosloterias.com/api/results';

        $response = Http::withOptions([
            'verify' => false, // Eliminar para producción
        ])->get($url);

        if ($response->successful()) {
            $lotteryResults = $response->json()['data'];

            if (empty($lotteryResults)) {
                return response()->json([
                    'data' => [],
                    'message' => 'No hay resultados disponibles en este momento.',
                ], 404);
            }

            $antioqueñitaTarde = collect($lotteryResults)->firstWhere('lottery', 'ANTIOQUEÑITA TARDE');

            if ($antioqueñitaTarde) {

                LotteryResult::updateOrCreate(
                    [
                        'slug' => $antioqueñitaTarde['slug'],
                        'date' => $antioqueñitaTarde['date'],
                    ],
                    [
                        'lottery' => $antioqueñitaTarde['lottery'],
                        'result' => $antioqueñitaTarde['result'],
                        'series' => $antioqueñitaTarde['series'],
                    ]
                );
            }

            return response()->json(['data' => $lotteryResults]);

        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener resultados de las loterías',
                'error_code' => $response->status(),
            ], 500);
        }
    }
}
