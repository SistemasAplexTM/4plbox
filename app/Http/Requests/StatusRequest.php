<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StatusRequest extends FormRequest
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
            'descripcion' => 'required',
            'color' => 'required',
            'email' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'descripcion.required' => 'La :attribute es obligatoria.',
            'color.required' => 'El :attribute es obligatorio.',
            'email.required' => 'El :attribute es obligatorio.',
        ];
    }

    public function attributes()
    {
        return [
            'descripcion' => 'Descripción',
            'color' => 'Color',
            'email' => 'Email',
        ];
    }
}
