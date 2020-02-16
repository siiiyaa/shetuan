<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//章节表
class Chapter extends Model
{
    protected $table = 'chapters';
    public $fillable = ['cha_name', 'course_id'];

    public function topic()
    {
        return $this->hasMany(Topic::class,'chapter_id','id');
    }

    public function insertChapter($data)
    {
        return $this->create($data);
    }

    public function section()
    {
        return $this->hasMany(Section::class, 'chapter_id','id');
    }
}
