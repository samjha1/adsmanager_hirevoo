<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $table = 'leadsmanager_campaigns';

    public const OBJECTIVES = [
        'leads' => 'Lead Generation',
        'awareness' => 'Brand Awareness',
        'traffic' => 'Traffic',
        'conversions' => 'Conversions',
        'app_installs' => 'App Installs',
    ];

    public const STATUSES = ['draft', 'active', 'paused', 'completed'];

    protected $fillable = [
        'user_id',
        'name',
        'objective',
        'status',
        'daily_budget',
        'total_budget',
        'spend',
        'start_date',
        'end_date',
        'description',
    ];

    protected $casts = [
        'daily_budget' => 'decimal:2',
        'total_budget' => 'decimal:2',
        'spend' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function ads()
    {
        return $this->hasMany(Ad::class, 'campaign_id');
    }

    public function leads()
    {
        return $this->hasMany(Lead::class, 'campaign_id');
    }

    public function objectiveLabel(): string
    {
        return self::OBJECTIVES[$this->objective] ?? ucfirst($this->objective);
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'active' => 'badge-green',
            'paused' => 'badge-amber',
            'completed' => 'badge-blue',
            default => 'badge-gray',
        };
    }
}
