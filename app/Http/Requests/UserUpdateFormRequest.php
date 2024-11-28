<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:225',
            'lastname' => 'required|string|max:225',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:225',
        ];
    }

    public function messages(){
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena.',
            'name.max' => 'El nombre no puede superar los 225 caracteres.',
            'lastname.required' => 'El apellido es obligatorio.',
            'lastname.string' => 'El apellido debe ser una cadena.',
            'lastname.max' => 'El apellido no puede superar los 225 caracteres.',
            'phone.required' => 'El número de teléfono es obligatorio.',
            'phone.string' => 'El número de teléfono debe ser una cadena.',
            'phone.max' => 'El número de teléfono no puede superar los 15 caracteres.',
            'address.required' => 'La dirección es obligatoria.',
            'address.string' => 'La dirección debe ser una cadena'
        ];
    }

    public function attributes(){
        return[
            'name' => 'Nombre',
            'lastname' => 'Apellido',
            'phone' => 'Número de teléfono',
            'address' => 'Dirección'
        ];
    }
}
