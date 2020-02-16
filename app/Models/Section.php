<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//小节表
class Section extends Model
{
    protected $table = 'sections';
    protected $fillable = ['sec_name', 'chapter_id'];

    public function insertSection($data)
    {
        return $this->create($data);
    }
}
