<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OdometerReading extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'starting_odometer',
        'ending_odometer',
        'distance_traveled',
        'log_type',
        'purpose',
        'maintenance_cost',
        'description',
    ];

    protected $casts = [
        'date' => 'date',
        'maintenance_cost' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}