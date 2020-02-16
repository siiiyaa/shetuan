<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class ChapterStoreRequest extends FormRequest
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
            'cha_name' => 'required|unique:chapters,cha_name'
        ];
    }

    public function messages()
    {
        return [
            'cha_name.required' => '章节名称不得为空',
            'cha_name.unique' => '此章节已存在',
        ];
    }
}
