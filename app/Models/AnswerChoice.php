<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnswerChoice extends Model
{
    protected $guarded = ['id'];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function user_answers()
    {
        return $this->hasMany(UserAnswer::class);
    }
}
