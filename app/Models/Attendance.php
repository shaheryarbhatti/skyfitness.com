<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'user_id',
        'checkin_time',
        'checkout_time',
        'attendance_date',
    ];

// Cast these as dates so you can format them easily in the UI
    protected $casts = [
        'checkin_time'    => 'datetime',
        'checkout_time'   => 'datetime',
        'attendance_date' => 'date',
    ];
}
