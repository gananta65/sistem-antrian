<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Staff extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'staff';

    protected $fillable = [
        'name',
        'email',
        'password',
        'counter_number',
        'is_active',
        'current_queue_id',
        'total_served',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'counter_number' => 'integer',
        'total_served' => 'integer',
        'role' => 'string',
    ];

    // Relationships
    public function queues()
    {
        return $this->hasMany(Queue::class, 'staff_id');
    }

    public function services()
    {
        return $this->hasMany(Service::class, 'staff_id');
    }

    public function currentQueue()
    {
        return $this->belongsTo(Queue::class, 'current_queue_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeWithCounter($query)
    {
        return $query->whereNotNull('counter_number');
    }
}