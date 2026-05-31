<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $table = 'leadsmanager_leads';

    public const STATUSES = ['new', 'contacted', 'qualified', 'proposal', 'won', 'lost'];

    public const SOURCES = [
        'hirevo_ad',
        'website',
        'referral',
        'social',
        'email',
        'cold_call',
        'event',
        'other',
    ];

    protected $fillable = [
        'user_id',
        'campaign_id',
        'ad_id',
        'name',
        'email',
        'phone',
        'company',
        'job_title',
        'source',
        'placement',
        'referrer_url',
        'status',
        'value',
        'notes',
        'meta',
        'next_followup_at',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'next_followup_at' => 'datetime',
        'meta' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }

    public function ad()
    {
        return $this->belongsTo(Ad::class, 'ad_id');
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'new' => 'badge-blue',
            'contacted' => 'badge-purple',
            'qualified' => 'badge-amber',
            'proposal' => 'badge-orange',
            'won' => 'badge-green',
            'lost' => 'badge-gray',
            default => 'badge-gray',
        };
    }
}
