<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Financial extends Model
{
    protected $fillable = [
        'month',
        'year',
        'total_revenue',
        'total_costs',
        'profit',
        'notes',
    ];

    protected $casts = [
        'month'         => 'integer',
        'year'          => 'integer',
        'total_revenue' => 'decimal:2',
        'total_costs'   => 'decimal:2',
        'profit'        => 'decimal:2',
    ];
}
