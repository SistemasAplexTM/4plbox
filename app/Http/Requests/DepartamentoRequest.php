<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepartamentoRequest extends FormRequest
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
            'pais_id' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'descripcion.required' => 'La :attribute es obligatoria.',
            'pais_id.required' => 'El :attribute es obligatorio',
        ];
    }

    public function attributes()
    {
        return [
            'descripcion' => 'Descripción',
            'pais_id' => 'País',
        ];
    }
}
