<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Friend extends Model
{
   	use SoftDeletes;
    const PENDING = 1, ACCEPTED = 2, BLOCKED = 3;

        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'to_user_id', 'status',
    ];
    /**
     * The attributes that should be returned as Carbon Instance
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('friends.status', self::PENDING);
    }

    public function scopeAccepted($query)
    {
        return $query->where('friends.status', self::ACCEPTED);
    }

    public function scopeBlocked($query)
    {
        return $query->where('friends.status', self::BLOCKED);
    }
}
