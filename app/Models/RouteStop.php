<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouteStop extends Model
{
    use HasFactory;

    protected $fillable = [
        'delivery_route_id',
        'customer_name',
        'address',
        'lat',
        'lng',
        'sequence_order',
        'status',
        'arrived_at',
        'delivered_at',
    ];

    public function route()
    {
        return $this->belongsTo(DeliveryRoute::class, 'delivery_route_id');
    }
}
