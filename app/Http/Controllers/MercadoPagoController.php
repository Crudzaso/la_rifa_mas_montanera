<?php

namespace App\Http\Controllers;

use App\Services\DiscordWebhookService;
use App\Services\MercadoPagoService;
use App\Models\Raffle;
use App\Models\TicketPurchase;
use App\Models\Tickets;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Events\BuyTicket;
use App\Helpers\EmailHelperGlobal;

class MercadoPagoController extends Controller
{
    protected $mercadoPagoService;
    private $emailHelperGlobal;
    protected $discordWebhookService;

    public function __construct(MercadoPagoService $mercadoPagoService,EmailHelperGlobal $emailHelperGlobal, DiscordWebhookService $discordWebhookService)
    {
        $this->mercadoPagoService = $mercadoPagoService;
        $this->emailHelperGlobal = $emailHelperGlobal;
        $this->discordWebhookService = $discordWebhookService;
        Log::info('MercadoPagoController iniciado.');
        Log::info('Listener SendDiscordNotification ejecutado');
    }

    /**
     * Crear preferencia de pago y devolver URL de redirección.
     */
    public function createPayment(Request $request)
    {
        try {
            $validated = $request->validate([
                'raffle_id' => 'required|exists:raffles,id',
                'ticket_numbers' => 'required|array'
            ]);

            // Guardar datos de compra en sesión
            session(['purchase_data' => $validated]);

            $raffle = Raffle::findOrFail($validated['raffle_id']);
            $user = Auth::user();

            $items = [[
                "title" => $raffle->title,
                "quantity" => count($validated['ticket_numbers']),
                "unit_price" => floatval($raffle->price_tickets),
                "currency_id" => "COP"
            ]];

            $payer = [
                "name" => $user->name,
                "email" => $user->email
            ];

            $preference = $this->mercadoPagoService->createPaymentPreference($items, $payer);

            return response()->json([
                'redirect_url' => $preference->init_point
            ]);

        } catch (\Exception $e) {
            Log::error('Error en createPayment:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Error al procesar el pago: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Acción cuando el pago es exitoso.
     */
    public function success(Request $request)
    {
        try {

            Log::info('Pago exitoso recibido:', $request->all());

            $paymentId = $request->get('payment_id');
            $payment = $this->mercadoPagoService->getPaymentStatus($paymentId);

            if ($payment && $payment->status == 'approved') {
                Log::info('Pago aprobado, procesando compra...', [
                    'payment_id' => $paymentId,
                    'user_id' => Auth::id()
                ]);

                DB::beginTransaction();

                // Obtener los datos de la sesión o de la preferencia de pago
                $purchaseData = session('purchase_data');
                if (!$purchaseData) {
                    throw new \Exception('No se encontraron datos de la compra');
                }

                $raffle = Raffle::findOrFail($purchaseData['raffle_id']);
                $ticketNumbers = $purchaseData['ticket_numbers'];

                // Crear tickets individuales
                foreach ($ticketNumbers as $number) {
                    Tickets::create([
                        'user_id' => Auth::id(),
                        'raffle_id' => $raffle->id,
                        'ticket_number' => $number,
                        'is_winner' => false
                    ]);
                }

                // Crear o actualizar el registro de compra
                $ticketPurchase = TicketPurchase::updateOrCreate(
                    [
                        'user_id' => Auth::id(),
                        'raffle_id' => $raffle->id
                    ],
                    [
                        'quantity' => DB::raw('quantity + ' . count($ticketNumbers)),
                        'payment_id' => $paymentId,
                        'payment_status' => 'approved'
                    ]
                );

                // Incrementar tickets vendidos en la rifa
                $raffle->increment('tickets_sold', count($ticketNumbers));

                // Enviar el mensaje a discord de confirmación de compra
                event(new BuyTicket(Auth::user(), $raffle, $ticketNumbers, $payment->transaction_amount));
                // Enviar el correo de confirmación de compra
                $this->emailHelperGlobal->sendTicketPurchaseEmail(Auth::user(), $raffle, $ticketNumbers);

                DB::commit();

                Log::info('Error al enviar el email');

                // Limpiar datos de sesión
                session()->forget('purchase_data');

                return redirect()->route('tickets.index')
                    ->with('message', 'Compra realizada con éxito');

            } else {
                Log::error('Estado del pago no válido:', ['status' => $payment->status ?? 'unknown']);
                throw new \Exception('El pago no fue aprobado');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en success:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('tickets.create', $purchaseData['raffle_id'] ?? null)
                ->with('error', 'Error al procesar la compra: ' . $e->getMessage());
        }
    }

    /**
     * Acción cuando el pago falla.
     */
    public function failure()
    {
        Log::error('El pago ha fallado.');
        return response()->json([
            'error' => 'El pago fallo.'
        ], 400);
    }
}
