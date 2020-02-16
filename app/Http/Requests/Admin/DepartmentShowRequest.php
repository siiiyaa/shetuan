<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentShowRequest extends FormRequest
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
            'search_name' => 'bail|required|max:20',
        ];
    }

    public function messages()
    {
        return [
            'search_name.required' => '搜索关键字不得为空',
            'search_name.max' => '搜索关键字不得大于20位',

        ];
    }
}
