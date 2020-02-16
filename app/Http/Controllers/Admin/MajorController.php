<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\MajorShowRequest;
use App\Http\Requests\Admin\MajorStoreRequest;
use App\Http\Requests\Admin\MajorUpdateRequest;
use App\Models\Department;
use App\Models\Major;
use App\Traits\BaseTrait;
use Doctrine\DBAL\Exception\DatabaseObjectExistsException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MajorController extends Controller
{
    use BaseTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Major $major)
    {
        $result = $major->with(['department:id,department_name'])->select('id','major_name','department_id')->get(['id','major_name','department_id']);

        foreach ($result as $value){
            $value['department_name'] = $value['department']['department_name'];
            unset($value['department']);
        }


        return $this->result($result,1,'success',200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Department $department)
    {
        $result = $department::where('type', 1)->get(['id', 'department_name']);
        $options = [];
        foreach ($result as $value){
            $result = [];
            $result['value'] = $value->id;
            $result['label'] = $value->department_name;
            $options[] = $result;
        }

        return $this->result($options,1,'success',200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MajorStoreRequest $request, Major $major)
    {
        $department_id = $request->input('department_id');
        $major_name = $request->input('major_name');
        $result = $major::insert([
            'department_id' => $department_id,
            'major_name' => $major_name,
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
    public function show(MajorShowRequest $request, Major $major)
    {
        $search_name = $request->input('search_name');

        $result = $major->with(['department:id,department_name'])
            ->where('major_name', 'like', '%'.$search_name.'%')
            ->orWhereHas('department', function ($query) use ($search_name){
            return $query->where('department_name', 'like','%'.$search_name.'%');
        })->get(['id', 'major_name', 'department_id']);

        foreach ($result as $value){
            $value['department_name'] = $value['department']['department_name'];
            unset($value['department']);
        }

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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MajorUpdateRequest $request, Major $major)
    {
        $major_id = $request->input('id');
        $major_name = $request->input('major_name');
        $department_id = $request->input('department_id');

        $result = $major::find($major_id);
        if($result){
            $result->major_name = $major_name;
            $result->department_id = $department_id;
            $face = $result->save();
            if($face){
                return $this->result('',1,'修改成功',200);
            }else{
                return $this->result('',0,'修改失败',200);
            }
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
    public function destroy(Request $request, Major $major)
    {
        $major_id = $request->input('major_id');
        $result = $major::find($major_id);

        if(!$result->classes->isEmpty()){
            return $this->result('',0,'无法删除！请先删除该专业下的班级',200);
        }else{
            $c = $result->delete();
            if($c){
                return $this->result('',1,'删除成功',200);
            }else{
                return $this->result('',0,'删除失败',200);
            }

        }
    }
}
