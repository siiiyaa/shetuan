<?php

namespace App\Models;

use App\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Teacher extends Model
{
    use BaseTrait;
    public $timestamps = false;
    public $table = 'teachers';

    public function association()
    {
        return $this->belongsToMany(Association::class,'teacher_association','teacher_id','association_id','id','id')->withPivot('status','is_admin')->withTimestamps();
    }

    public function getHeadImageAttribute($path)
    {
        if(empty($path)) {
           $path = DB::table('systems')->find(1)->teacher_default_images;
        }

        return env('APP_IMAGE_URL').$path;
    }
}
