<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConsigneeRequest extends FormRequest
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
            // 'tipo_identificacion_id' => 'required|integer',
            // 'agencia_id' => 'required|integer',
            'localizacion_id' => 'required|integer',
            // 'documento' => 'required',
            'primer_nombre' => 'required',
            // 'primer_apellido' => 'required',
            // 'correo' => 'email',
            'direccion' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'tipo_identificacion_id.required' => 'El :attribute es obligatorio.',
            // 'agencia_id.required' => 'La :attribute es obligatoria.',
            // 'agencia_id.integer' => 'Debe seleccionar una :attribute de la lista.',
            'localizacion_id.required' => 'La :attribute es obligatoria.',
            'localizacion_id.integer' => 'Debe seleccionar una :attribute de la lista.',
            'documento.required' => 'El :attribute es obligatorio.',
            'primer_nombre.required' => 'El :attribute es obligatorio.',
            // 'primer_apellido.required' => 'El :attribute es obligatorio.',
            'correo.unique' => 'El :attribute ya esiste en la base de datos.',
            'correo.email' => 'El :attribute debe ser una dirección de correo electrónico válida.',
            'direccion.required' => 'La :attribute es obligatoria.',
        ];
    }

    public function attributes()
    {
        return [
            'tipo_identificacion_id' => 'Tipo de identificación',
            'localizacion_id' => 'Ciudad',
            // 'agencia_id' => 'Agencia',
            'documento' => 'Documento',
            'primer_nombre' => 'Primer Nombre',
            // 'primer_apellido' => 'Primer Apellido',
            'correo' => 'Correo',
            'direccion' => 'Dirección',
        ];
    }
}
