<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'full_name',
        'nik',
        'place_of_birth',
        'date_of_birth',
        'gender',
        'blood_type',
        'religion',
        'marital_status',
        'occupation',
        'citizenship',
        'email',
        'phone',
        'address',
        'photo',
        'status',
    ];

    // Optional: cast some fields
    protected $casts = [
        'date_of_birth'     => 'date',
        'status'            => 'boolean',
    ];

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}
