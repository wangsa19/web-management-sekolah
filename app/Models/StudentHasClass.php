<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentHasClass extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function students(){
        return $this->belongsTo(Student::class);
    }
    
    public function homerooms(){
        return $this->belongsTo(HomeRoom::class, 'homerooms_id', 'id');
    }
}
