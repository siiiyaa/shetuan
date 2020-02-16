<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\AssociationUpdateRequest;
use App\Http\Requests\Teacher\StoreAssociationRequest;
use App\Models\Association;
use App\Models\Department;
use App\Models\StudentAssociationExperience;
use App\Traits\BaseTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AssociationManageController extends Controller
{
    use BaseTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Association $association)
    {
        $result = $association::select('id','ass_name','images','department_id')->with(['department:id,department_name'])->paginate(10);

        //getCollection是获取分页里面的数据集合 transform是迭代集合并对集合内的每个项目调用特定的回调
        $result->getCollection()->transform(function ($value) {
            $value->setAppends([]);
            return $value;
        });

        return $this->result($result,1,'success',200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Department $department)
    {
        $result = $department->get(['id','department_name']);

        return $this->result($result,1,'success',200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAssociationRequest $request, Association $association)
    {

        $data = $this->controlData($request);

        $data['teacher_id'] = $request->input('teacher_id');
        $result = $association->addAssociation($data);

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
    public function show(Request $request, Association $association)
    {
        $association_id = $request->input('association_id');
        if(!$association_id){
            return response()->json([
                'data' => ['association_id' => ['参数不完整']],
                'code' => 422,
                'message' => 'error'
            ],422);
        }

        $result = $association::with(['teacher' => function($query) use ($association_id){
            $teacher_id = \DB::table('teacher_association')->where('association_id', $association_id)->where('status',1)->where('is_admin',1)->first(['teacher_id'])->teacher_id;
            return $query->where('teachers.id', $teacher_id)->select('teachers.id');
        }])->where('id', $association_id)->select('id','ass_name', 'english_name', 'corporate_slogan', 'introduce', 'images', 'learning_objectives', 'department_id')->first();
        $result->teacher_id = $result->teacher[0]->id;
        unset($result->teacher);


        return $this->result($result,1,'success',200);
    }

    public function searchAssociation(Request $request, Association $association)
    {
        $search_name = $request->input('search_name');
        if(!$search_name){
            return response()->json([
                'data' => ['search_name' => ['搜索关键字不得为空']],
                'code' => 422,
                'message' => 'error'
            ],422);
        }

        $result = $association::with(['department:id,department_name'])->where('ass_name', 'like', '%'.$search_name.'%')->orWhereHas('department',function ($query) use ($search_name){
            $query->where('department_name', 'like', '%'.$search_name.'%');
        })->select('id', 'ass_name', 'images', 'department_id')->paginate(2);

        //getCollection是获取分页里面的数据集合 transform是迭代集合并对集合内的每个项目调用特定的回调
        $result->getCollection()->transform(function ($value) {
            $value->setAppends([]);
            return $value;
        });

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
    public function update(AssociationUpdateRequest $request, Association $association)
    {
        $data = $this->controlData($request);

        $data['teacher_id'] = $request->input('teacher_id');

        $result = $association->updateAssociation($data);

        if($result){
            return $this->result('',1,'修改成功',200);
        }else{
            return $this->result('',0,'修改失败',200);
        }
    }

    public function controlData(Request $request)
    {
        $data = $request->all();

        if($request->hasFile('images')){
            $data['images'] = $request->file('images');
        }elseif($request->input('images')){
            $data['images'] = $request->input('images');
        }else{
            $data['images'] = '';
        }

        return $data;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Association $association, StudentAssociationExperience $studentAssociationExperience)
    {
        $association_id = $request->input('association_id');
        if(!$association_id){
            return response()->json([
                'data' => ['association_id' => ['参数不完整']],
                'code' => 422,
                'message' => 'error'
            ],422);
        }

        $association_result = $association::find($association_id);
        if($association_result){
            \DB::beginTransaction();
            try{
                $association_result->student()->detach();
                $association_result->teacher()->detach();
                $association_result->delete();
                $studentAssociationExperience::where('association_id', $association_id)->delete();
                \DB::commit();
                return $this->result('',1,'删除成功',200);
            }catch(\Exception $exception){
                return $this->result('',0,'删除失败',200);
            }
        }else{
            return $this->result('',0,'删除失败',200);
        }

    }
}
