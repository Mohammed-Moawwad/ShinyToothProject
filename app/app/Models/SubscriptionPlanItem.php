<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlanItem extends Model
{
    protected $fillable = [
        'plan_id',
        'service_id',
        'assigned_dentist_id',
        'order_index',
        'status',
        'notes',
        'completed_at',
    ];

    protected $casts = [
        'order_index'  => 'integer',
        'completed_at' => 'datetime',
    ];

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function assignedDentist()
    {
        return $this->belongsTo(Dentist::class, 'assigned_dentist_id');
    }
}
