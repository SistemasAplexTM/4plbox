<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TipoDocumentoRequest extends FormRequest
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
            'prefijo' => 'required',
            'icono' => 'required',
            'consecutivo_inicial' => 'required',
            'credenciales' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'nombre.required' => 'El :attribute es obligatorio.',
            'prefijo.required' => 'El :attribute es obligatorio',
            'icono.required' => 'El :attribute es obligatorio',
            'consecutivo_inicial.required' => 'El :attribute es obligatorio',
            'credenciales.required' => 'Las :attribute son obligatorias.',
        ];
    }

    public function attributes()
    {
        return [
            'nombre' => 'Nombre',
            'prefijo' => 'Prefijo',
            'icono' => 'Icono',
            'consecutivo_inicial' => 'Consecutivo inicial',
            'credenciales' => 'Credenciales',
        ];
    }
}
