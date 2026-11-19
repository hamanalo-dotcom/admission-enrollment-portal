<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'event_date',
        'type',
        'is_active',
    ];

    protected $casts = [
        'event_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Scope to get only active events
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get upcoming events
     */
    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', Carbon::today())
            ->orderBy('event_date', 'asc');
    }
}
