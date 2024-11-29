<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;


class StoreRequest extends FormRequest
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
            'name' => 'required|min:3|max:255',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:8|max:22',
        ];
    }


    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El campo nombre es requerido',
            'name.min' => 'El campo nombre debe tener al menos 3 caracteres',
            'name.max' => 'El campo nombre debe tener máximo 255 caracteres',
            'email.required' => 'El campo email es requerido',
            'email.unique' => 'El email ya se encuentra registrado',
            'password.required' => 'El campo contraseña es requerido',
            'password.min' => 'El campo contraseña debe tener al menos 8 caracteres',
            'password.max' => 'El campo contraseña debe tener máximo 22 caracteres',
        ];
    }
}
