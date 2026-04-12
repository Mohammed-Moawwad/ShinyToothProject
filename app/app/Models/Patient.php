<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Patient extends Authenticatable
{
    use HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'date_of_birth',
        'gender',
        'address',
        'blood_type',
        'place_of_birth',
        'nationality',
        'booking_blocked',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'date_of_birth'   => 'date',
        'password'        => 'hashed',
        'booking_blocked' => 'boolean',
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function ratings()
    {
        return $this->hasMany(DoctorRating::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(DoctorSubscription::class);
    }

    public function activeSubscription()
    {
        return $this->hasOne(DoctorSubscription::class)
                    ->whereIn('status', ['active', 'pending']);
    }
}
