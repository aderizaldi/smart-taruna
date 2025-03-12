<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $guarded = ['id'];

    public function package_exams()
    {
        return $this->hasMany(PackageExam::class);
    }
}
