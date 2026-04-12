<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorSubscription extends Model
{
    protected $fillable = [
        'patient_id',
        'dentist_id',
        'status',
        'admin_action_status',
        'requested_at',
        'accepted_at',
        'completed_at',
        'rejection_reason',
        'patient_switch_reason',
        'patient_cancel_reason',
        'admin_removal_reason',
        'doctor_removal_reason',
        'switch_to_dentist_id',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'accepted_at'  => 'datetime',
        'completed_at' => 'datetime',
    ];

    /* ── Relationships ─────────────────────────── */

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function dentist()
    {
        return $this->belongsTo(Dentist::class);
    }

    public function switchToDentist()
    {
        return $this->belongsTo(Dentist::class, 'switch_to_dentist_id');
    }

    public function plan()
    {
        return $this->hasOne(SubscriptionPlan::class, 'subscription_id');
    }

    public function rating()
    {
        return $this->hasOne(SubscriptionRating::class, 'subscription_id');
    }

    public function bonus()
    {
        return $this->hasOne(SubscriptionBonus::class, 'subscription_id');
    }

    /* ── Scopes ────────────────────────────────── */

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeForPatient($query, $patientId)
    {
        return $query->where('patient_id', $patientId);
    }
}
