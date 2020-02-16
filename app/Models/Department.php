<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'departments';

    public $timestamps = true;

    public $hidden = ['type','created_at','updated_at'];

    public $appends = ['type_value'];

    public function major()
    {
        return $this->hasMany(Major::class, 'department_id', 'id');
    }

    public function getTypeValueAttribute()
    {

        return $this->type;

    }

    public function DepartmentSelectValue()
    {
        $options = [];
        $result  = $this->all(['id','department_name']);
        foreach($result as $value){
            $department_arr =  [];
            $department_arr['value'] = $value['id'];
            $department_arr['label'] = $value['department_name'];
            $options[] = $department_arr;
        }

        return $options;
    }
}
