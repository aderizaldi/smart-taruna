<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAnswer extends Model
{
    protected $guarded = ['id'];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function answerChoice()
    {
        return $this->belongsTo(AnswerChoice::class);
    }

    public function user_exam()
    {
        return $this->belongsTo(UserExam::class);
    }
}
