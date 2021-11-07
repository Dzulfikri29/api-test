<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MutationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules['date'] = 'required';
        $rules['amount'] = 'required';
        $rules['type'] = 'required';
        $rules['source'] = 'required';
        $rules['note'] = 'required';

        return $rules;
    }
}
