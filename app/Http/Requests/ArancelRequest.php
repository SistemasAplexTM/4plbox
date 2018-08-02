<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArancelRequest extends FormRequest
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
            'pa' => 'required',
            'arancel' => 'required',
            'iva' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'descripcion.required' => 'La :attribute es obligatoria.',
            'pa.required' => 'El :attribute es obligatorio',
            'arancel.required' => 'El :attribute es obligatorio',
            'iva.required' => 'El :attribute es obligatorio',
        ];
    }

    public function attributes()
    {
        return [
            'descripcion' => 'Descripción',
            'pa' => 'Posicion arancelaria',
            'arancel' => 'Arancel',
            'iva' => 'IVA',
        ];
    }
}
