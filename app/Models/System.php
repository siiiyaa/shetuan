<?php

namespace App\Models;

use App\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Model;

class System extends Model
{
    use BaseTrait;
    public $table = 'systems';

    public $timestamps = false;

    protected $fillable = ['association_store_on'];

    public function getStudentDefaultImagesAttribute($path)
    {
//        $path = $this->pathToBase64($path);

        return env('APP_IMAGE_URL').$path;
    }

    public function getTeacherDefaultImagesAttribute($path)
    {
//        $path = $this->pathToBase64($path);

        return env('APP_IMAGE_URL').$path;
    }

    public function getAssociationDefaultImagesAttribute($path)
    {
//        $path = $this->pathToBase64($path);

        return env('APP_IMAGE_URL').$path;
    }

    public function getActivityDefaultImagesAttribute($path)
    {
//        $path = $this->pathToBase64($path);

        return env('APP_IMAGE_URL').$path;
    }



}
