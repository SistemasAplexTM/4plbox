<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'agencia_id' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'El :attribute es obligatorio',
            'name.string' => 'El :attribute debe ser alfanumerico.',
            'name.max' => 'El :attribute es de maximo 255 caracteres.',
            'email.required' => 'El :attribute es obligatorio',
            'email.string' => 'El :attribute debe ser alfanumerico.',
            'email.email' => 'El :attribute ingresado no es una direccion de correo valida.',
            'email.max' => 'El :attribute es de maximo 255 caracteres.',
            'email.unique' => 'El :attribute ingresado, ya esta registrado.',
            'password.required' => 'El :attribute es obligatorio',
            'password.string' => 'El :attribute debe ser alfanumerico.',
            'password.max' => 'El :attribute debe de ser como minimo 6 caracteres.',
            'password.confirmed' => 'Las contraseñas no son iguales',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Nombre',
            'email' => 'Correo',
            'password' => 'Constraseña',
            'agencia_id' => 'Agencia'
        ];
    }
}
