<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MaestraMultipleRequest extends FormRequest
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
            'modulo' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'nombre.required' => 'El :attribute es obligatorio.',
            'modulo.required' => 'El :attribute es obligatorio.'
        ];
    }

    public function attributes()
    {
        return [
            'nombre' => 'Nombre',
            'modulo' => 'Modulo'
        ];
    }
}
