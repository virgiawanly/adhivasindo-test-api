<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Tool extends BaseModel
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tools';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'image',
    ];
}
