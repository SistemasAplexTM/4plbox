<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AerolineasAeropuertosRequest extends FormRequest
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
            'nombre' => 'required',
            'localizacion_id' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'nombre.required' => 'El :attribute es obligatorio.',
            'localizacion_id.required' => 'La :attribute es obligatoria.',
        ];
    }

    public function attributes()
    {
        return [
            'nombre' => 'Nombre',
            'localizacion_id' => 'Ciudad',
        ];
    }
}
