<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptimalPath extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'optimal_path',
        'total_weight',
        'optimize_type',
        'locations',
    ];

    protected $casts = [
        'locations' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}