<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrackingRequest extends FormRequest
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
            'codigo' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'consignee_id.required' => 'El :attribute es obligatorio.',
            'codigo.required' => 'El :attribute es obligatorio.',
        ];
    }

    public function attributes()
    {
        return [
            'consignee_id' => 'Cliente',
            'codigo' => 'Tracking',
        ];
    }
}
