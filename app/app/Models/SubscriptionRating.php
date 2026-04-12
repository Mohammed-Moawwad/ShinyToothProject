<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionRating extends Model
{
    protected $fillable = [
        'subscription_id',
        'patient_id',
        'rating',
        'review',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function subscription()
    {
        return $this->belongsTo(DoctorSubscription::class, 'subscription_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
