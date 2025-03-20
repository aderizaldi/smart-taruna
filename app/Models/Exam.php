<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $guarded = ['id'];

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function user_exams()
    {
        return $this->hasMany(UserExam::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
