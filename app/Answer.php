<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $table = 'answers';
    protected $primaryKey = 'answer_id';
    public $incrementing = true;

    protected $hidden = ['created_at', 'updated_at'];
    protected $fillable = ['user_id', 'module_id', 'uploads'];
}
