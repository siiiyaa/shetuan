<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentAssociationExperience extends Model
{
    public $timestamps = false;

    protected $table = 'student_association_experience';

    protected $fillable = ['association_id', 'student_id', 'experience'];





}
