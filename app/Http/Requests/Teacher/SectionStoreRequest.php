<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class SectionStoreRequest extends FormRequest
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
            'sec_name' => 'required|unique:sections,sec_name'
        ];
    }

    public function messages()
    {
        return [
            'sec_name.required' => '小节名称不得为空',
            'sec_name.unique' => '此小节已存在',
        ];
    }
}
