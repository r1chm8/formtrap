<?php

namespace App;

use Laravel\Cashier\Billable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Billable, HasApiTokens, Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getGravatarUrlAttribute()
    {
        return vsprintf('https://gravatar.com/avatar/%s', [
            md5(strtolower(trim($this->email)))
        ]);
    }

    public function forms()
    {
        return $this->hasMany(Form::class, 'user_id');
    }
}
