<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'type',
        'is_active',
        'published_at',
        'expiration_date',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'published_at' => 'datetime',
        'expiration_date' => 'datetime',
    ];

    /**
     * Scope to get only active announcements
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function($q) {
                $q->whereNull('published_at')
                  ->orWhere('published_at', '<=', now());
            })
            ->where(function($q) {
                $q->whereNull('expiration_date')
                  ->orWhere('expiration_date', '>=', now());
            })
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Automatically deactivate expired announcements
     * This should be called before querying announcements to ensure expired ones are deactivated
     */
    public static function deactivateExpired()
    {
        return static::where('is_active', true)
            ->whereNotNull('expiration_date')
            ->where('expiration_date', '<', now())
            ->update(['is_active' => false]);
    }
}
