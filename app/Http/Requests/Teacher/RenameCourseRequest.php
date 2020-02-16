<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RenameCourseRequest extends FormRequest
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
        $type = request()->input('type');
        if($type){
            if($type == 1){
                return $this->courseRules();
            }elseif ($type == 2){
                return $this->chapterRules();
            }elseif ($type == 3){
                return $this->sectionRules();
            }
        }else{
            throw (new HttpResponseException(response()->json([
                'data' => ['id' => ['参数不完整']],
                'code' => 422,
                'message' => 'error'
            ],422)));
        }

    }


    protected function courseRules()
    {
        return [
            'id' => 'bail|required',
            'name' => 'required|unique:courses,cou_name,'.request()->input('id')
        ];
    }

    protected function chapterRules()
    {
        return [
            'id' => 'bail|required',
            'name' => 'required|unique:chapters,cha_name,'.request()->input('id')
        ];
    }

    protected function sectionRules()
    {
        return [
            'id' => 'bail|required',
            'name' => 'required|unique:sections,sec_name,'.request()->input('id')
        ];
    }


    public function messages()
    {
        $data = $this->typeMessage();
        return [
            'id.required' => '参数不完整',
            'name.required' => $data['requireed_message'],
            'name.unique' =>  $data['requireed_unique'],
        ];
    }

    public function typeMessage()
    {
        $type =  request()->input('type');
        if($type == 1){
            $data['requireed_message'] = '课程名字不得为空';
            $data['requireed_unique'] = '此课程已存在';
        }elseif ($type == 2){
            $data['requireed_message'] = '章节名字不得为空';
            $data['requireed_unique'] = '此章节已存在';
        }elseif ($type == 3){
            $data['requireed_message'] = '小节名字不得为空';
            $data['requireed_unique'] = '此小节已存在';
        }

        return $data;
    }
}
