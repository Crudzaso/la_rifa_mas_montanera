<?php

namespace App\Http\Requests\Raffles;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class updateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        $raffle = $this->route('raffle');

        return [
            'title' => 'required|min:3|max:255',
            'description' => 'nullable',
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
            'status' => ['required', Rule::in(['pending', 'ongoing', 'finished'])],
            'url_image' => 'nullable|url',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'El título es obligatorio',
            'title.min' => 'El título debe tener al menos 3 caracteres',
            'start_date.required' => 'La fecha de inicio es obligatoria',
            'end_date.required' => 'La fecha de fin es obligatoria',
            'end_date.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio',
            'status.required' => 'El estado es obligatorio',
            'status.in' => 'El estado seleccionado no es válido',
            'url_image.url' => 'La URL de la imagen no es válida',
        ];
    }
}
