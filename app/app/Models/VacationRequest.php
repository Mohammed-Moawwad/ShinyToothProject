<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VacationRequest extends Model
{
    protected $fillable = [
        'dentist_id',
        'type',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'reason',
        'status',
        'admin_note',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function dentist()
    {
        return $this->belongsTo(Dentist::class);
    }

    public function getDaysCountAttribute(): int
    {
        if (is_null($this->end_date)) {
            return 1;
        }

        return $this->start_date->diffInDays($this->end_date) + 1;
    }
}
