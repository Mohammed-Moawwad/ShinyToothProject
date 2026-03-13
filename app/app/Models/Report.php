<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'title',
        'type',
        'start_date',
        'end_date',
        'generated_by',
        'data',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'data'       => 'array',
    ];
}
