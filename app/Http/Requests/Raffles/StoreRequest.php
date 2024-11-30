<?php

namespace App\Http\Requests\Raffles;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
            'prize' => 'required|string|max:255',
            'start_date' => [
                'required',
                'date_format:Y-m-d', // Forzar formato específico
                'after_or_equal:today',
            ],
            'end_date' => [
                'required',
                'date_format:Y-m-d',
                'after_or_equal:start_date',
            ],
            'price_tickets' => 'required|in:2000,5000,10000,20000,50000', 
            'url_image' => 'nullable|url',
        ];
    }

     /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'El título de la rifa es obligatorio.',
            'prize.required' => 'El premio es obligatorio.',
            'start_date.required' => 'La fecha de inicio es obligatoria.',
            'end_date.required' => 'La fecha de finalización es obligatoria.',
            'price_tickets.required' => 'Debe seleccionar un precio para la boleta.',
            'total_tickets.required' => 'El número total de boletos es obligatorio.',
            'total_tickets.min' => 'El número total de boletos debe ser al menos 1.',
            'total_tickets.max' => 'El número total de boletos no puede ser mayor a 100.',
            'url_image.url' => 'La URL de la imagen debe ser una URL válida.',

        ];
    }
}