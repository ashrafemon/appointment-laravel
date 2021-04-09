<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function workinghours()
    {
        return $this->hasMany(WorkingHour::class);
    }

    public function breakhours()
    {
        return $this->hasMany(BreakHour::class);
    }

    public function timeoffs()
    {
        return $this->hasMany(Timeoff::class);
    }

    public function userservices()
    {
        return $this->hasOne(UserService::class);
    }

    public function appoints()
    {
        return $this->hasMany(Appoint::class);
    }

    public function workingstatuses(){
        return $this->hasMany(WorkingStatus::class);
    }
}
