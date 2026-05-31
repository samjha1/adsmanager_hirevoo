<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'leadsmanager_users';

    protected $fillable = [
        'name',
        'email',
        'company',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function leads()
    {
        return $this->hasMany(Lead::class, 'user_id');
    }

    public function campaigns()
    {
        return $this->hasMany(Campaign::class, 'user_id');
    }

    public function ads()
    {
        return $this->hasMany(Ad::class, 'user_id');
    }

    public function isAdmin(): bool
    {
        if ($this->role === 'admin') {
            return true;
        }

        $emails = array_filter(array_map('trim', explode(',', (string) config('leadsmanager.admin_emails', ''))));

        return $emails !== [] && in_array(strtolower($this->email), array_map('strtolower', $emails), true);
    }

    public function initials(): string
    {
        $name = trim((string) $this->name);
        if ($name === '') {
            return '?';
        }

        $parts = preg_split('/\s+/u', $name, -1, PREG_SPLIT_NO_EMPTY);
        if (count($parts) >= 2) {
            return mb_strtoupper(mb_substr($parts[0], 0, 1).mb_substr(end($parts), 0, 1), 'UTF-8');
        }

        return mb_strtoupper(mb_substr($parts[0], 0, 2), 'UTF-8');
    }
}
