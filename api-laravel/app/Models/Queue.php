<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    use HasFactory;

    protected $fillable = [
        'queue_number',
        'type',
        'status',
        'staff_id',
        'called_at',
        'completed_at',
    ];

    protected $casts = [
        'called_at' => 'datetime',
        'completed_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    // Relationships
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function service()
    {
        return $this->hasOne(Service::class, 'queue_id');
    }

    // Scopes
    public function scopeWaiting($query)
    {
        return $query->where('status', 'waiting');
    }

    public function scopeCalled($query)
    {
        return $query->where('status', 'called');
    }

    public function scopeDone($query)
    {
        return $query->where('status', 'done');
    }

    public function scopeReservation($query)
    {
        return $query->where('type', 'reservation');
    }

    public function scopeWalkin($query)
    {
        return $query->where('type', 'walkin');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }
}