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

        // Verificar si la rifa tiene tickets vendidos
        if ($raffle->tickets_sold > 0) {
            return [
                'title' => 'prohibited',
                'description' => 'prohibited',
                'start_date' => 'prohibited',
                'end_date' => 'prohibited',
                'url_image' => 'prohibited',
                'status' => 'prohibited'
            ];
        }

        return [
            'title' => 'required|min:3|max:255',
            'description' => 'nullable',
            'start_date' => [
                'required',
                'date_format:Y-m-d',
                'after_or_equal:today',
            ],
            'end_date' => [
                'required',
                'date_format:Y-m-d',
                'after_or_equal:start_date',
                function ($attribute, $value, $fail) {
                    if (!$value) return;

                    $date = \Carbon\Carbon::createFromFormat('Y-m-d', $value);
                    if ($date && $date->isDayOfWeek(\Carbon\Carbon::SUNDAY)) {
                        $fail('La rifa no puede terminar en domingo.');
                    }
                }
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
            'end_date.not_in' => 'La rifa no puede terminar en domingo.',
            'title.prohibited' => 'No se puede modificar la rifa porque ya tiene tickets vendidos.',
            'description.prohibited' => 'No se puede modificar la rifa porque ya tiene tickets vendidos.',
            'start_date.prohibited' => 'No se puede modificar la rifa porque ya tiene tickets vendidos.',
            'end_date.prohibited' => 'No se puede modificar la rifa porque ya tiene tickets vendidos.',
            'url_image.prohibited' => 'No se puede modificar la rifa porque ya tiene tickets vendidos.',
            'status.prohibited' => 'No se puede modificar la rifa porque ya tiene tickets vendidos.',
        ];
    }
}
