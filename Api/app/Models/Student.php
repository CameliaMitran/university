<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';
    protected $guarded = [];
    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
