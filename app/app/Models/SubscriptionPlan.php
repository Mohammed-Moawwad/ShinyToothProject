<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $fillable = [
        'subscription_id',
        'title',
        'notes',
        'total_price',
        'created_by_dentist_id',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
    ];

    public function subscription()
    {
        return $this->belongsTo(DoctorSubscription::class, 'subscription_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(Dentist::class, 'created_by_dentist_id');
    }

    public function items()
    {
        return $this->hasMany(SubscriptionPlanItem::class, 'plan_id')->orderBy('order_index');
    }

    /**
     * Recalculate total_price from service prices of all items.
     */
    public function recalculateTotal(): void
    {
        $this->total_price = $this->items()
            ->join('services', 'services.id', '=', 'subscription_plan_items.service_id')
            ->sum('services.price');
        $this->save();
    }
}
