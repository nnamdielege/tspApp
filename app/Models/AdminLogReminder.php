<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminLogReminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'employee_id',
        'reminder_date',
        'reminder_type',
        'sent',
        'sent_at',
        'acknowledged',
        'acknowledged_at',
        'notes',
    ];

    protected $casts = [
        'reminder_date' => 'date',
        'sent_at' => 'datetime',
        'acknowledged_at' => 'datetime',
        'sent' => 'boolean',
        'acknowledged' => 'boolean',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function markAsSent()
    {
        $this->update([
            'sent' => true,
            'sent_at' => now(),
        ]);
    }

    public function markAsAcknowledged()
    {
        $this->update([
            'acknowledged' => true,
            'acknowledged_at' => now(),
        ]);
    }
}