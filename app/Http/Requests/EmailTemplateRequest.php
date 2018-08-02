<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmailTemplateRequest extends FormRequest
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
            'agencia_id' => 'required',
            'nombre' => 'required',
            'subject' => 'required',
            'mensaje' => 'required',
            'descripcion_plantilla' => 'required'
        ];
    }
    public function messages()
    {
        return [
            'agencia_id.required' => 'La :attribute es obligatoria.',
            'nombre.required' => 'El :attribute es obligatorio.',
            'subject.required' => 'El :attribute es obligatorio.',
            'mensaje.required' => 'El :attribute es obligatorio.',
            'descripcion_plantilla.required' => 'La :attribute es obligatoria.',
        ];
    }

    public function attributes()
    {
        return [
            'agencia_id' => 'Agencia',
            'nombre' => 'Nombre',
            'subject' => 'Subject',
            'mensaje' => 'Mensaje',
            'descripcion_plantilla' => 'Descipcion de la plantilla',
        ];
    }
}
