<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'user_id';
    public $incrementing = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $hidden = ['password'];
    protected $fillable = [
        'fullname', 
        'email', 
        'contact', 
        'students_number', 
        'field_of_study', 
        'institution', 
        'usertype', 
        'password'
    ]; 
}
