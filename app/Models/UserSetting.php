<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email_notifications',
        'end_of_day_reminder',
        'missing_log_notification',
        'route_completion_notification',
        'theme',
        'language',
        'timezone',
        '24_hour_format',
        'show_location_history',
        'show_distance_traveled',
        'allow_admin_view_logs',
        'default_optimization',
        'auto_start_route',
        'auto_complete_route',
        'vehicle_number',
        'vehicle_model',
        'vehicle_notes',
        'reminder_time_hours_before',
        'daily_summary_email',
        'preferred_reminder_time',
    ];

    protected $casts = [
        'email_notifications' => 'boolean',
        'end_of_day_reminder' => 'boolean',
        'missing_log_notification' => 'boolean',
        'route_completion_notification' => 'boolean',
        '24_hour_format' => 'boolean',
        'show_location_history' => 'boolean',
        'show_distance_traveled' => 'boolean',
        'allow_admin_view_logs' => 'boolean',
        'auto_start_route' => 'boolean',
        'auto_complete_route' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}