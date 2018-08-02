<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StatusReportRequest extends FormRequest
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
            'status_id' => 'required'
        ];
    }
    public function messages()
    {
        return [
            'status_id.required' => 'El :attribute es obligatorio.'
        ];
    }

    public function attributes()
    {
        return [
            'status_id' => 'Estatus'
        ];
    }
}
