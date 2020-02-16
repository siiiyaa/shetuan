<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\DepartmentShowRequest;
use App\Http\Requests\Admin\DepartmentStoreRequest;
use App\Http\Requests\Admin\DepartmentUpdateRequest;
use App\Models\Department;
use App\Traits\BaseTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DepartmentController extends Controller
{
    use BaseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Department $department)
    {
        $result = $department::all(['id','department_name','type']);

        return $this->result($result,1,'success',200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DepartmentStoreRequest $request, Department $department)
    {
        $department_name = $request->input('department_name');
        $type = $request->input('type');

        $result = $department::insert([
            'department_name' => $department_name,
            'type' => $type,
        ]);
        if($result){
            return $this->result('',1,'新增成功',200);
        }else{
            return $this->result('',0,'新增失败',200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(DepartmentShowRequest $request, Department $department)
    {
        $search_name = $request->input('search_name');

        $result = $department::where('department_name','like','%'.$search_name.'%')->get(['id','department_name']);

        return $this->result($result,1,'success',200);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DepartmentUpdateRequest $request, Department $department)
    {

        $department_id = $request->input('id');
        $department_name = $request->input('department_name');
        $type = $request->input('type_value');

        $result = $department::find($department_id);
        if($result){
            $result->department_name = $department_name;
            $result->type = $type;
            $result->save();
            return $this->result('',1,'修改成功',200);
        }else{
            return $this->result('',0,'修改失败',200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Department $department)
    {
        $department_id = $request->input('department_id');

        $department_result = $department::find($department_id);
        if(!$department_result->major->isEmpty()){
            return $this->result('',0,'删除失败，请先删除此系下的所有专业',200);
        }else{
            $department_result->delete();
            return $this->result('',1,'删除成功',200);
        }
    }


}
