<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function suspendedByAdmin()
    {
        return $this->belongsTo(User::class, 'suspended_by');
    }

    public function suspendedEmployees()
    {
        return $this->hasMany(User::class, 'suspended_by');
    }

    public function suspend($reason = null)
    {
        $this->update([
            'is_suspended' => true,
            'suspended_at' => now(),
            'suspended_by' => auth()->id(),
            'suspension_reason' => $reason,
        ]);
    }

    public function unsuspend()
    {
        $this->update([
            'is_suspended' => false,
            'unsuspended_at' => now(),
        ]);
    }

    public function isSuspended()
    {
        return $this->is_suspended === true;
    }

    public function settings()
    {
        return $this->hasOne(UserSetting::class);
    }

    public function getOrCreateSettings()
    {
        return $this->settings()->firstOrCreate([
            'user_id' => $this->id,
        ]);
    }
}