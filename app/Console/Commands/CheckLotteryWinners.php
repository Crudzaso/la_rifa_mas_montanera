<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Lotteries\ProcessLotteryResults;

class CheckLotteryWinners extends Command
{
    protected $signature = 'lottery:check-winners';
    protected $description = 'Verifica los ganadores de las rifas basado en resultados de la lotería antioqueñita tarde';

    public function __construct(
        private ProcessLotteryResults $action
    ) {
        parent::__construct();
    }

    public function handle()
    {
        try {
            $result = $this->action->execute();
            
            if (empty($result['data'])) {
                $this->warn('No hay resultados disponibles para procesar.');
                return;
            }

            $this->info('Verificación de ganadores completada exitosamente.');
            
        } catch (\Exception $e) {
            $this->error('Error al verificar ganadores: ' . $e->getMessage());
            return 1;
        }
    }
}
