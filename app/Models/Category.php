<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
   	use SoftDeletes;
    const ACTIVE = 1, INACTIVE = 0;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id', 'name', 'status',
    ];
    /**
     * The attributes that should be returned as Carbon Instance
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('categories.status', self::ACTIVE);
    }

    public function scopeInActive($query)
    {
        return $query->where('categories.status', self::INACTIVE);
    }
}
