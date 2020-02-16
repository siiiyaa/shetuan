<?php

namespace App\Http\Requests\Teacher;

use App\Rules\EmptyArray;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class AddAssessmentRequest extends FormRequest
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
    public function rules(Request $request)
    {
        $rule = [
            'association_id' => 'required',
            'ass_name' => 'required',
            'clipping_time' => 'bail|required|numeric',
            'topic_ids' => 'bail|required|array',
            'experience' => 'bail|required|numeric'
        ];

        if($request->hasFile('study_avi')){
            $rule['study_avi'] = 'bail|mimetypes:video/mp4,video/avi|max:30720|mimes:mp4,avi';
        }

        return $rule;
    }

    public function messages()
    {
        return [
            'study_avi.mimetypes' => '视频格式必须为MP4或avi',
            'study_avi.mimes' => '视频格式必须为MP4或avi',
            'study_avi.max' => '前置学习视频不得超过30m',
            'association_id.required' => '参数不完整',
            'ass_name.required' => '考试标题不得为空',
            'clipping_time.required' => '考试时间',
            'clipping_time.numeric' => '考试时间必须为数字',
            'topic_ids.required' => '至少添加一个题目',
            'topic_ids.array' => '参数错误',
            'experience.required' => '经验值不得为空',
            'experience.numeric' => '参数错误'

        ];
    }
}
