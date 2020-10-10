<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SavePost extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'post_id', 'user_id',
    ];
    /**
     * The attributes that should be returned as Carbon Instance
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
}
