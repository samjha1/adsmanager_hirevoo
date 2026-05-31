<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Ad extends Model
{
    use HasFactory;

    protected $table = 'leadsmanager_ads';

    public const PLACEMENTS = [
        'hirevo_homepage' => 'Hirevo — Homepage Hero',
        'hirevo_jobs' => 'Hirevo — Job Listings',
        'hirevo_dashboard' => 'Hirevo — Candidate Dashboard',
        'hirevo_sidebar' => 'Hirevo — Sidebar Banner',
        'hirevo_email' => 'Hirevo — Email Newsletter',
    ];

    public const STATUSES = ['draft', 'pending_review', 'active', 'paused', 'archived'];

    /** Statuses advertisers may set themselves (not active — requires admin approval). */
    public const ADVERTISER_STATUSES = ['draft', 'pending_review', 'paused', 'archived'];

    public static function statusLabels(): array
    {
        return [
            'draft' => 'Draft',
            'pending_review' => 'In review',
            'active' => 'Live on Hirevo',
            'paused' => 'Paused',
            'archived' => 'Archived',
        ];
    }

    public function statusLabel(): string
    {
        return self::statusLabels()[$this->status] ?? ucfirst(str_replace('_', ' ', (string) $this->status));
    }

    public function isLive(): bool
    {
        return $this->status === 'active'
            && ($this->relationLoaded('campaign')
                ? $this->campaign?->status === 'active'
                : $this->campaign()->where('status', 'active')->exists());
    }

    /**
     * Status options the advertiser may pick when editing (keys = stored values).
     */
    public function advertiserStatusOptions(): array
    {
        $labels = self::statusLabels();

        return match ($this->status) {
            'active' => [
                'active' => $labels['active'].' (current)',
                'paused' => 'Pause on Hirevo',
                'archived' => $labels['archived'],
            ],
            'pending_review' => [
                'pending_review' => $labels['pending_review'].' (current)',
                'draft' => 'Save as draft',
                'archived' => $labels['archived'],
            ],
            'paused' => [
                'paused' => $labels['paused'].' (current)',
                'pending_review' => 'Submit for review again',
                'draft' => $labels['draft'],
                'archived' => $labels['archived'],
            ],
            'archived' => [
                'archived' => $labels['archived'].' (current)',
                'draft' => 'Restore to draft',
            ],
            default => [
                'draft' => $labels['draft'],
                'pending_review' => 'Submit for review',
                'archived' => $labels['archived'],
            ],
        };
    }

    public function statusHelpText(): string
    {
        return match ($this->status) {
            'active' => 'Your ad is approved and visible on Hirevo. Pause it to hide, or archive when finished.',
            'pending_review' => 'Waiting for approval in the Hirevo admin panel (Sponsored ads). You cannot go live until approved.',
            'paused' => 'Hidden on Hirevo. Submit for review again when you want it back.',
            'archived' => 'Archived ads are not shown. Restore to draft to edit and resubmit.',
            default => 'Save as draft to keep working, or submit for review when ready to go live on Hirevo.',
        };
    }

    protected $fillable = [
        'campaign_id',
        'user_id',
        'name',
        'headline',
        'body',
        'image_path',
        'image_url',
        'cta_label',
        'destination_url',
        'placement',
        'status',
        'target_area',
        'target_age_group',
        'target_audience',
        'public_key',
        'impressions_count',
        'clicks_count',
        'leads_count',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $ad) {
            if (empty($ad->public_key)) {
                $ad->public_key = (string) Str::uuid();
            }
        });

        static::saved(function (self $ad) {
            if (! $ad->wasChanged('image_path') || ! $ad->image_path) {
                return;
            }
            $url = self::storagePublicUrl($ad->image_path);
            if ($url && $ad->getAttributes()['image_url'] !== $url) {
                $ad->forceFill(['image_url' => $url])->saveQuietly();
            }
        });
    }

    /**
     * Public URL for a file on the "public" disk (requires php artisan storage:link).
     */
    public static function storagePublicUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        return Storage::disk('public')->url(str_replace('\\', '/', $path));
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => self::storagePublicUrl($this->image_path) ?? $value,
        );
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function leads()
    {
        return $this->hasMany(Lead::class, 'ad_id');
    }

    public function placementLabel(): string
    {
        return self::PLACEMENTS[$this->placement] ?? ucfirst(str_replace('_', ' ', $this->placement));
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'active' => 'badge-green',
            'pending_review' => 'badge-orange',
            'paused' => 'badge-amber',
            'archived' => 'badge-gray',
            default => 'badge-blue',
        };
    }

    public function ctr(): float
    {
        return $this->impressions_count > 0
            ? round(($this->clicks_count / $this->impressions_count) * 100, 2)
            : 0.0;
    }

    public function conversionRate(): float
    {
        return $this->clicks_count > 0
            ? round(($this->leads_count / $this->clicks_count) * 100, 2)
            : 0.0;
    }
}
