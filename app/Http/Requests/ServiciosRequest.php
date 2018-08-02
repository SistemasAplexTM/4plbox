<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiciosRequest extends FormRequest
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
            'tarifa' => 'required|numeric',
            'cobro_opcional' => 'required|numeric',
            'peso_minimo' => 'required|numeric',
            'seguro' => 'required|numeric',
            'impuesto' => 'required|numeric',
            'tipo_embarque_id' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'nombre.required' => 'El :attribute es obligatorio.',
            'tarifa.required' => 'La :attribute es obligatoria.',
            'tarifa.numeric' => 'La :attribute debe ser un dato numerico.',
            'cobro_opcional.required' => 'El :attribute es obligatorio.',
            'cobro_opcional.numeric' => 'El :attribute debe ser un dato numerico.',
            'peso_minimo.required' => 'El :attribute es obligatorio.',
            'peso_minimo.numeric' => 'El :attribute debe ser un dato numerico.',
            'seguro.required' => 'El :attribute es obligatorio.',
            'seguro.numeric' => 'El :attribute debe ser un dato numerico.',
            'impuesto.required' => 'El :attribute es obligatorio.',
            'impuesto.numeric' => 'El :attribute debe ser un dato numerico.',
            'tipo_embarque_id.required' => 'El :attribute es obligatorio.',
        ];
    }

    public function attributes()
    {
        return [
            'nombre' => 'Nombre',
            'tarifa' => 'Tarifa',
            'cobro_opcional' => 'Cobro opcional',
            'peso_minimo' => 'Peso minimo',
            'seguro' => 'Seguro',
            'impuesto' => 'Impuesto',
            'tipo_embarque_id' => 'Tipo embarque',
        ];
    }
}
