<?php

namespace Mangosteen\Models\Entities;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    /**
     * @var string
     */
    protected $table = 'users';
    /**
     * @var string[]
     */
    protected $fillable = ['name', 'email', 'password', 'role_id', 'google_uid', 'facebook_uid', 'password_uid', 'avatar', 'email_verified',];
    /**
     * @var string[]
     */
    protected $hidden = ['password',];

}
