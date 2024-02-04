<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class StoreContactRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|min:5',
            'contact' => [
                'required',
                'digits:9',
                Rule::unique('contacts')->whereNull('deleted_at')
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('contacts')->whereNull('deleted_at')
            ],
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new ValidationException($validator, response()->json($validator->errors(), 422));
    }
}
