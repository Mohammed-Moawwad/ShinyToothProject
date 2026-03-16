<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Dentist extends Authenticatable
{
    use HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'salary',
        'hire_date',
        'status',
        'place_of_birth',
        'nationality',
        'experience_years',
        'university',
        'image',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'hire_date'        => 'date',
        'salary'           => 'decimal:2',
        'experience_years' => 'integer',
        'password'         => 'hashed',
    ];

    public function specializations()
    {
        return $this->belongsToMany(Specialization::class, 'dentist_specialization');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function ratings()
    {
        return $this->hasMany(DoctorRating::class);
    }
}
