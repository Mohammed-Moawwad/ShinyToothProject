<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specialization extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function dentists()
    {
        return $this->belongsToMany(Dentist::class, 'dentist_specialization');
    }
}
