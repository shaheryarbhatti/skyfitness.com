<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_no',
        'member_id',
        'fee',
        'start_date',
        'end_date',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id')->withDefault([
            'full_name' => 'Unknown Member'
        ]);
    }

    protected $casts = [
        'fee' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'created_at' => 'datetime', // Already handled by default, but good to know
    ];
}
