<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    const ACTIVE = 1, INACTIVE = 0;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'mobile', 'email', 'password', 'avatar', 'fcm_token', 'device_id', 'login_type', 'account_type', 'status', 'gender'
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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The attributes that should be returned as Carbon Instance
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function friends()
    {
        return $this->belongsToMany(Friend::class, 'user_id');
    }

    public function friend()
    {
        return $this->hasOne(Friend::class, 'to_user_id');
    }

    public function generateApiToken()
    {
        return encrypt([
            'user_id' => $this->id,
            'user_type' => self::class,
        ]);
    }

    public function fullName()
    {
        return $this->first_name.' '.$this->last_name;
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('users.status', self::ACTIVE);
    }

    public function scopeInActive($query)
    {
        return $query->where('users.status', self::INACTIVE);
    }
}
