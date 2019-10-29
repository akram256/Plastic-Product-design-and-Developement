<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = 'questions';
    protected $primaryKey = 'question_id';
    public $incrementing = true;

    protected $hidden = ['created_at', 'updated_at'];
    protected $fillable = ['title', 'question'];
}
