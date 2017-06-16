<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * 通过openid获取用户
     * @param $openid
     * @return User|bool
     */
    public static function getUserFromOpenid($openid){
        if($user = User::where('remember_token',$openid)->first()){
            return $user;
        }
        return false;
    }
}
