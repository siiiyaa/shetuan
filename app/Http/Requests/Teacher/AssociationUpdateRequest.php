<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class AssociationUpdateRequest extends FormRequest
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
            'association_id' => 'required',
            'introduce' => 'bail|required|max:300',
        ];
    }

    public function messages()
    {
        return [
            'association_id.required' => '参数不完整',
            'introduce.required' => '社团介绍不得为空',
            'introduce.max' => '社团介绍不能超过300个字符',
        ];
    }
}
