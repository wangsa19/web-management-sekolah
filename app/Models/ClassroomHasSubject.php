<?php

namespace App\Models;

use App\Models\Subject;
use App\Models\Classroom;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClassroomHasSubject extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'classroom_subject';

    public function subjects(){
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    public function class(){
        return $this->belongsTo(Classroom::class);
    }
}
