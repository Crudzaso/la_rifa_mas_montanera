<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateRequest extends FormRequest
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
           'name' => 'min:3|max:255',
            'email' => 'email|unique:users,email',
            
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
            'name.min' => 'El campo nombre debe tener al menos 3 caracteres.',
            'name.max' => 'El campo nombre debe tener máximo 255 caracteres.',
            'email.unique' => 'El correo ya se encuentra registrado.',
            'email.email' => 'El correo no es válido.',
            'email.min' => 'El correo debe tener al menos 3 caracteres.',
            'email.max' => 'El correo debe tener máximo 255 caracteres.',
        ];
    }
}
