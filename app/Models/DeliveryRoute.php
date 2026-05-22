<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryRoute extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'route_date',
        'status',
    ];

    public function stops()
    {
        return $this->hasMany(RouteStop::class)->orderBy('sequence_order');
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
}
