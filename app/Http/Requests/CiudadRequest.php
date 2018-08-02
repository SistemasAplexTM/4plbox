<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CiudadRequest extends FormRequest
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
            'pais_id' => 'required',
            'deptos_id' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'nombre.required' => 'El :attribute es obligatorio.',
            'pais_id.required' => 'El :attribute es obligatorio',
            'deptos_id.required' => 'El :attribute es obligatorio',
        ];
    }

    public function attributes()
    {
        return [
            'nombre' => 'Nombre',
            'pais_id' => 'País',
            'deptos_id' => 'Departamento',
        ];
    }
}
