<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Requests\Teacher\AddCourseRequest;
use App\Http\Requests\Teacher\ChapterStoreRequest;
use App\Http\Requests\Teacher\DeleteCourseRequest;
use App\Http\Requests\Teacher\RenameCourseRequest;
use App\Http\Requests\Teacher\SectionStoreRequest;
use App\Models\Association;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\Section;
use App\Traits\BaseTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CourseManageController extends Controller
{
    use BaseTrait;

    public function courseIndex(Request $request, Association $association)
    {
        $association_id = $request->input('association_id');
        if(!$association_id){
            return response()->json([
                'data' => ['association_id' => ['参数不完整']],
                'code' => 422,
                'message' => 'error'
            ],422);
        }
        $result = $association->with('course:id,cou_name,association_id')->where('id',$association_id)->first();
        if($result){
           $result = array_map(function ($item){
                unset($item['association_id']);
                return $item;
            }, $result->course->toArray());
            return $this->result($result,1,'success',200);
        }else{
            return $this->result([],1,'success',200);
        }

    }

    public function showChapter(Request $request, Chapter $chapter)
    {
        $id = $request->input('course_id');
        if(!$id){
            return response()->json([
                'data' => ['course_id' => ['参数不完整']],
                'code' => 422,
                'message' => 'error'
            ],422);
        }
        $result = $chapter->where('course_id', $id)->get(['id','cha_name']);

        return $this->result($result,1,'success',200);
    }

    public function showSection(Section $section, Request $request)
    {
        $id = $request->input('chapter_id');
        if(!$id){
            return response()->json([
                'data' => ['chapter_id' => ['参数不完整']],
                'code' => 422,
                'message' => 'error'
            ],422);
        }
        $result = $section->where('chapter_id', $id)->get(['id','sec_name']);

        return $this->result($result,1,'success',200);
    }



    public function courseStore(AddCourseRequest $request, Course $course)
    {
        $data = $request->all();

        $result = $course->insertCourse($data);
        if($result){
            $result = collect($result)->only(['id','cou_name']);
            return $this->result($result,1,'新增课程成功',200);
        }else{
            return $this->result('',1,'新增课程失败',200);
        }
    }

    public function chapterStore(ChapterStoreRequest $request, Chapter $chapter)
    {
        $data = $request->all();
        $result = $chapter->insertChapter($data);
        if($result){
            $result = collect($result)->only(['id','cha_name']);
            return $this->result($result,1,'新增章节成功',200);
        }else{
            return $this->result('',1,'新增章节失败',200);
        }
    }

    public function sectionStore(SectionStoreRequest $request, Section $section)
    {
        $data = $request->all();
        $result = $section->insertSection($data);
        if($result){
            $result = collect($result)->only(['sec_name', 'id']);
            return $this->result($result,1,'新增小节成功',200);
        }else{
            return $this->result('',1,'新增小节失败',200);
        }
    }

    public function rename(RenameCourseRequest $request, Course $course, Chapter $chapter, Section $section)
    {
        $type = $request->input('type');
        $id = $request->input('id');
        $name = $request->input('name');
        if ($type == 1){
            $result = $this->updateCourseName($id, $name, $course);
        }elseif ($type == 2){
            $result = $this->updateChapterName($id, $name, $chapter);
        }elseif ($type == 3){
            $result = $this->updateSectionName($id, $name, $section);
        }

        if($result){
            return $this->result('',1,'修改成功', 200);
        }else{
            return $this->result('',0,'修改失败', 200);
        }
    }

    public function delete(DeleteCourseRequest $request, Course $course, Chapter $chapter, Section $section){
        $type = $request->input('type');
        $id = $request->input('id');
        if ($type == 1){
            return $this->destroyCourse($id, $course);
        }elseif ($type == 2){
            return $this->destroyChapter($id, $chapter);
        }elseif ($type == 3){
            return $this->destroySection($id, $section);
        }

    }

    public function show()
    {

    }


    public function edit($id)
    {
        //
    }


    protected function updateCourseName($id, $name, Course $course)
    {
        $result = $course->find($id);
        $result->cou_name = $name;
        return $result->save();
    }

    protected function updateChapterName($id, $name, Chapter $chapter)
    {
        $result = $chapter->find($id);
        $result->cha_name = $name;
        return $result->save();
    }

    protected function updateSectionName($id, $name, Section $section)
    {
        $result = $section->find($id);
        $result->sec_name = $name;
        return $result->save();
    }



    public function destroyCourse($id, $course)
    {
        $course_result = $course->find($id);
        $result = $course_result->topic;

        if($result->isEmpty()){
            $course_result->delete();
            return $this->result('',1,'删除成功',200);
        }else{
            return $this->result('',0,'此章节下有题目、活动，尚无法被删除',200);
        }

    }

    public function destroyChapter($id, $chapter)
    {
        $chapter_result = $chapter->find($id);
        $result = $chapter_result->topic;

        if($result->isEmpty()){
            $chapter_result->delete();
            return $this->result('',1,'删除成功',200);
        }else{
            return $this->result('',0,'此章节下有题目、活动，尚无法被删除',200);
        }

    }

    public function destroySection($id, $section)
    {
        $result = $section->find($id)->delete();
        if($result){
            return $this->result('',1,'删除成功',200);
        }else{
            return $this->result('',0,'删除失败',200);

        }

    }

    public function treeIndex(Request $request, Course $course)
    {
        $association_id = $request->input('association_id');

        $result = $course::whereHas('association',function ($query) use ($association_id){
            $query->where('associations.id',$association_id);
        })->with(['chapter.section'])->get();

        $result->transform(function ($value){
           unset($value->created_at);
           unset($value->association_id);
           unset($value->updated_at);
           $value['id'] = $value->id;
           $value['label'] = $value->cou_name;
           $value['type'] = 1;
           unset($value->cou_name);

           if($value->chapter){
               $value->chapter->transform(function ($item) {
                   unset($item->created_at);
                   unset($item->course_id);
                   unset($item->updated_at);
                   $item['id'] = $item->id;
                   $item['label'] = $item->cha_name;
                   $item['type'] = 2;
                   unset($item->cha_name);

                   if( $item->section){
                       $item->section->transform(function ($item){
                           unset($item->created_at);
                           unset($item->chapter_id);
                           unset($item->updated_at);
                           $item['label'] = $item->sec_name;
                           $item['type'] = 3;
                           unset( $item->sec_name);
                           return $item;
                       });
                       $item['children'] = $item->section;
                        unset($item->section);
                   }
                   return $item;
               });
               $value['children'] = $value->chapter;
               unset($value->chapter);
           }

           return $value;
        });
        return $this->result($result,1,'success',200);



    }
}
