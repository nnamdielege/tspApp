<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptimalPath extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_id',
        'optimal_path',
        'total_weight',
        'optimize_type',
        'status',
        'locations',
        'started_at',
        'completed_at',
        'route_notes',
    ];

    protected $casts = [
        'locations' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function odometerReadings()
    {
        return $this->hasMany(OdometerReading::class);
    }

    public function getLocationCountAttribute()
    {
        return count($this->locations ?? []);
    }

    public function markAsStarted()
    {
        $this->update([
            'status' => 'in_progress',
            'started_at' => now(),
        ]);
    }

    public function markAsCompleted()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }
}