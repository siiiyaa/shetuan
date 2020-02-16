<?php

namespace App\Http\Requests\teacher;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCorporateSloganRequest extends FormRequest
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
            'association_id' => 'required|exists:associations,id',
            'corporate_slogan' => 'max:200',
        ];
    }

    public function messages()
    {
        return [
            'association_id.required' => '参数不完整',
            'association_id.exists' => '参数错误',
            'corporate_slogan.max' => '口号不得超过200个字符'
        ];
    }
}
