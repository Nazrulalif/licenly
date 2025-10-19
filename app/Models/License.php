<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class License extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'license_id',
        'product_key',
        'customer_id',
        'rsa_key_id',
        'license_type',
        'status',
        'max_devices',
        'features',
        'hardware_id',
        'issue_date',
        'expiry_date',
        'revoked_at',
        'revocation_reason',
        'pem_content',
        'license_data',
        'signature',
    ];

    protected $casts = [
        'features' => 'array',
        'license_data' => 'array',
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'revoked_at' => 'datetime',
    ];

    // Relationships
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function rsaKey(): BelongsTo
    {
        return $this->belongsTo(RsaKey::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'ACTIVE');
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'EXPIRED');
    }

    public function scopeRevoked($query)
    {
        return $query->where('status', 'REVOKED');
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->where('status', 'ACTIVE')
            ->whereBetween('expiry_date', [now(), now()->addDays($days)]);
    }

    // Accessors
    public function getDaysRemainingAttribute(): int
    {
        if ($this->status !== 'ACTIVE') {
            return 0;
        }

        $diff = now()->diffInDays($this->expiry_date, false);
        return max(0, (int) $diff);
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->expiry_date->isPast() && $this->status === 'ACTIVE';
    }

    public function getIsActiveAttribute(): bool
    {
        return $this->status === 'ACTIVE' && !$this->is_expired;
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'ACTIVE' => '<span class="badge badge-light-success">Active</span>',
            'EXPIRED' => '<span class="badge badge-light-danger">Expired</span>',
            'REVOKED' => '<span class="badge badge-light-warning">Revoked</span>',
            'PENDING' => '<span class="badge badge-light-info">Pending</span>',
            default => '<span class="badge badge-light-secondary">Unknown</span>',
        };
    }

    public function getLicenseTypeBadgeAttribute(): string
    {
        return match ($this->license_type) {
            'TRIAL' => '<span class="badge badge-light-info">Trial</span>',
            'PERSONAL' => '<span class="badge badge-light-primary">Personal</span>',
            'PROFESSIONAL' => '<span class="badge badge-light-success">Professional</span>',
            'ENTERPRISE' => '<span class="badge badge-light-warning">Enterprise</span>',
            'CUSTOM' => '<span class="badge badge-light-dark">Custom</span>',
            default => '<span class="badge badge-light-secondary">Unknown</span>',
        };
    }

    // Helper Methods
    public function canBeExtended(): bool
    {
        return in_array($this->status, ['ACTIVE', 'EXPIRED']);
    }

    public function canBeRevoked(): bool
    {
        return $this->status === 'ACTIVE';
    }

    public function canBeDeleted(): bool
    {
        return in_array($this->status, ['EXPIRED', 'REVOKED']);
    }
}
