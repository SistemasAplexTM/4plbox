<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransportadorRequest extends FormRequest
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
            'direccion' => 'required',
            'telefono' => 'required',
            'ciudad' => 'required',
            'estado' => 'required',
            'pais' => 'required',
            // 'contacto' => 'required',
            // 'zip' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'nombre.required' => 'El :attribute es obligatorio',
            'direccion.required' => 'La :attribute es obligatoria',
            'telefono.required' => 'El :attribute es obligatorio',
            'ciudad.required' => 'La :attribute es obligatoria',
            'estado.required' => 'El :attribute es obligatorio',
            'pais.required' => 'El :attribute es obligatorio',
            // 'contacto.required' => 'El :attribute es obligatorio',
            // 'zip.required' => 'El :attribute es obligatorio',
        ];
    }

    public function attributes()
    {
        return [
            'nombre' => 'Nombre',
            'direccion' => 'Dirección',
            'telefono' => 'Teléfono',
            'ciudad' => 'Ciudad',
            'estado' => 'Estado',
            'pais' => 'País',
            // 'contacto' => 'Contacto',
            // 'zip' => 'ZIP-code',
        ];
    }
}
