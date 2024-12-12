<?php

namespace App\Http\Requests\Tickets;

use App\Models\Tickets;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'raffle_id' => 'required|exists:raffles,id',
            'ticket_numbers' => 'required|array|min:1',
            'ticket_numbers.*' => [
                'required',
                'string',
                'size:2',
                'regex:/^[0-9]{2}$/',
                function ($attribute, $value, $fail) {
                    if (Tickets::where('raffle_id', $this->raffle_id)
                            ->where('ticket_number', $value)
                            ->exists()) {
                        $fail("El boleto número $value ya ha sido vendido.");
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'ticket_numbers.required' => 'Debe seleccionar al menos un boleto.',
            'ticket_numbers.array' => 'El formato de los boletos no es válido.',
            'ticket_numbers.*.required' => 'El número de boleto es requerido.',
            'ticket_numbers.*.size' => 'El número de boleto debe tener 2 dígitos.',
            'ticket_numbers.*.regex' => 'El número de boleto debe estar entre 00 y 99.',
        ];
    }
}
