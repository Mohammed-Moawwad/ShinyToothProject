<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorRating extends Model
{
    protected $fillable = [
        'patient_id',
        'dentist_id',
        'appointment_id',
        'rating',
        'review',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function dentist()
    {
        return $this->belongsTo(Dentist::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
