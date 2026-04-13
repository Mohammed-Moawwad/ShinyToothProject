<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorWrittenReport extends Model
{
    use HasFactory;

    protected $table = 'doctor_written_reports';

    protected $fillable = [
        'dentist_id',
        'patient_id',
        'title',
        'content',
        'status',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function dentist()
    {
        return $this->belongsTo(Dentist::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
