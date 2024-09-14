<?php

namespace App\Models;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Classroom extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    public function subjects(){
        return $this->belongsToMany(Subject::class)->withPivot('description');
    }
}
