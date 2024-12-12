<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Lotteries\ProcessLotteryResults;

class LotteryController extends Controller
{
    public function getResults(ProcessLotteryResults $action)
    {
        try {
            $result = $action->execute();
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'error_code' => $e->getCode(),
            ], 500);
        }
    }
}
