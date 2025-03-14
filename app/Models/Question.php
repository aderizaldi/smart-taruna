<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $guarded = ['id'];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function answer_choices()
    {
        return $this->hasMany(AnswerChoice::class);
    }

    public function user_answers()
    {
        return $this->hasMany(UserAnswer::class);
    }
}
