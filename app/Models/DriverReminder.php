<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverReminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'optimal_path_id',
        'reminder_type',
        'reminder_date',
        'is_completed',
        'completed_at',
        'notes',
    ];

    protected $casts = [
        'reminder_date' => 'date',
        'completed_at' => 'datetime',
        'is_completed' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function optimalPath()
    {
        return $this->belongsTo(OptimalPath::class);
    }

    public function markAsCompleted()
    {
        $this->update([
            'is_completed' => true,
            'completed_at' => now(),
        ]);
    }
}