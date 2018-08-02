<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AgenciaRequest extends FormRequest
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
        switch ($this->method()) {
            case 'GET':
            case 'DELETE':
                {
                    return [];
                }
            case 'POST':
                {
                    return [
                        'descripcion' => 'required',
                        'responsable' => 'required',
                        'direccion' => 'required',
                        'telefono' => 'required',
                        'zip' => 'required',
                        'email' => 'required|email|unique:agencia,email',
                        'localizacion_id' => 'required|integer',
                    ];
                }
            case 'PUT':{
                 return [
                        'descripcion' => 'required',
                        'responsable' => 'required',
                        'direccion' => 'required',
                        'telefono' => 'required',
                        'zip' => 'required',
                        'email' => 'required|email',
                        'localizacion_id' => 'required|integer',
                    ];
            }
            case 'PATCH':
                {
                    return [
                        'descripcion' => 'required',
                        'responsable' => 'required',
                        'direccion' => 'required',
                        'telefono' => 'required',
                        'zip' => 'required',
                        'email' => 'required|email',
                        'localizacion_id' => 'required|integer',
                    ];
                }
            default:break;
        }
        return [
            
        ];
    }
    public function messages()
    {
        return [
            'descripcion.required' => 'El :attribute es obligatorio.',
            'responsable.required' => 'El :attribute es obligatorio.',
            'direccion.required' => 'La :attribute es obligatoria.',
            'telefono.required' => 'El :attribute es obligatorio.',
            'zip.required' => 'El :attribute es obligatorio.',
            'email.required' => 'El :attribute es obligatorio.',
            'email.unique' => 'El :attribute ya existe en la base de datos.',
            'email.email' => 'El :attribute debe ser un email valido.',
            'localizacion_id.required' => 'La :attribute es obligatoria.',
        ];
    }

    public function attributes()
    {
        return [
            'descripcion' => 'Nombre',
            'responsable' => 'Responsable',
            'direccion' => 'Dirección',
            'telefono' => 'Teléfono',
            'zip' => 'ZIP code',
            'email' => 'Email',
            'localizacion_id' => 'Ciudad',
        ];
    }
}
