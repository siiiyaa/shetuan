<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ClassShowRequest;
use App\Http\Requests\Admin\ClassStoreRequest;
use App\Http\Requests\Admin\ClassUpdateRequest;
use App\models\Classes;
use App\Models\Department;
use App\Traits\BaseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClassesController extends Controller
{
    use BaseTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Classes $classes)
    {
        $major_id = $request->input('major_id');

        $query = $classes->query();
        if($major_id){
            $query->where('major_id', $major_id);
        }
        $class_result = $query->with(['major:id,major_name,department_id', 'major.department:id,department_name'])
            ->select('id', 'cls_name', 'major_id')->get();

        return $this->result($class_result, 1, 'success', 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Department $department)
    {
        $result = $this->selectData($department);

        return $this->result($result, 1, 'success', 200);
    }

    protected function selectData($department)
    {
        $department_result = $department::where('type', 1)->get(['id', 'department_name']);

        $department = [];
        foreach ($department_result as $value) {
            $department_arr = [];
            $department_arr['value'] = $value->id;
            $department_arr['label'] = $value->department_name;

            $department_arr['children'] = [];
            foreach ($value->major as $value) {
                $major_arr = [];
                $major_arr['value'] = $value->id;
                $major_arr['label'] = $value->major_name;

                $department_arr['children'][] = $major_arr;
            }
            $department[] = $department_arr;
        }

        return $department;
    }

    public function screenData(Department $department)
    {
        $result = $this->selectData($department);

        return $this->result($result,1,'success',200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClassStoreRequest $request, Classes $classes)
    {
        $major_id = $request->input('major_id');
        $class_name = $request->input('class_name');

        $result = $classes::insert([
            'cls_name' => $class_name,
            'major_id' => $major_id,
        ]);

        if($result){
            return $this->result('',1,'添加成功',200);
        }else{
            return $this->result('',0,'添加失败',200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ClassShowRequest $request,Classes $classes)
    {
        $search_name = $request->input('search_name');
        $major_id = $request->input('major_id');

        $query = $classes->query();
        if($major_id){
            $query->where('major_id', $major_id);
        }


        $result = $query->with(['major:id,major_name,department_id', 'major.department:id,department_name'])
            ->where('cls_name', 'like', '%'.$search_name.'%')
            ->select('id', 'cls_name', 'major_id')
            ->get();

        return $this->result($result,1,'success',200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Department $department)
    {
        $result = $this->selectData($department);

        return $this->result($result,1,'success',200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ClassUpdateRequest $request, Classes $classes)
    {
        $class_id = $request->input('id');
        $major_id = $request->input('major_id');

        $class_name = $request->input('class_name');

        $class_result = $classes::find($class_id);
        if($class_result){
            $class_result->cls_name = $class_name;
            $class_result->major_id = $major_id;
            $result = $class_result->save();
            if($result){
                return $this->result('',1,'修改成功',200);
            }else{
                return $this->result('',0,'修改失败',200);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Classes $classes)
    {
        $class_id = $request->input('class_id');

        $result = $classes::find($class_id);
        if($result){
            if($result->student->isEmpty()){
                $result = $result->delete();
                if($result){
                    return $this->result('',1,'删除成功',200);
                }else{
                    return $this->result('',0,'删除失败',200);
                }
            }else{
                return $this->result('',0,'无法删除，请先删除此班级下的所有学生',200);
            }
        }else{
            return $this->result('',0,'删除失败',200);
        }
    }
}
