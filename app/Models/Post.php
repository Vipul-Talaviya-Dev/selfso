<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
	use SoftDeletes;
    const ACTIVE = 1, INACTIVE = 0;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'media', 'description', 'link', 'likes', 'dislikes', 'status', 'type'
    ];
    /**
     * The attributes that should be returned as Carbon Instance
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function tagFriends()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('posts.status', self::ACTIVE);
    }

    public function scopeInActive($query)
    {
        return $query->where('posts.status', self::INACTIVE);
    }
}
