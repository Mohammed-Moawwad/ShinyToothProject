<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionBonus extends Model
{
    protected $fillable = [
        'subscription_id',
        'dentist_id',
        'plan_total',
        'bonus_amount',
        'rating',
        'is_paid',
    ];

    protected $casts = [
        'plan_total'    => 'decimal:2',
        'bonus_amount'  => 'decimal:2',
        'rating'        => 'integer',
        'is_paid'       => 'boolean',
    ];

    public function subscription()
    {
        return $this->belongsTo(DoctorSubscription::class, 'subscription_id');
    }

    public function dentist()
    {
        return $this->belongsTo(Dentist::class);
    }
}
