<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateFormRequest extends FormRequest
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
            'name' => ['required','string','max:255'],
            'lastname' => ['required','string','max:255'],
            'email' => ['required','string','email','max:255','unique:users'],
            'phone' => ['required','string','max:15'],
            'address' => ['required','string','max:255'],
            'password' => ['required','string','min:8','confirmed'],
        ];
    }

    public function messages(){
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena.',
            'name.max' => 'El nombre no puede superar los 255 caracteres.',
            'lastname.required' => 'El apellido es obligatorio.',
            'lastname.string' => 'El apellido debe ser una cadena.',
            'lastname.max' => 'El apellido no puede superar los 255 caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser un email válido.',
            'email.max' => 'El correo electrónico no puede superar los 255 caracteres.',
            'email.unique',
            'password.required' => 'la contraseña es requerida',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'phone.required' => 'El número de teléfono es obligatorio.',
            'address.required' => 'la direccion es requerida.'
            ];
    }

    public function attributes(){
        return[
            'name' => 'Nombre',
            'lastname' => 'Apellido',
            'email' => 'Correo electrónico',
            'password' => 'Contraseña',
            'phone' => 'Número de teléfono',
            'address' => 'Dirección'
            ];

    }
}
