<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverChecklist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'check_type',
        'odometer_reading',
        'checklist_items',
        'notes',
    ];

    protected $casts = [
        'checklist_items' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}