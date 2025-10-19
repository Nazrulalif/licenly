<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Customer extends Model
{
    use HasFactory, SoftDeletes, Searchable, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_name',
        'contact_name',
        'email',
        'phone',
        'address',
        'country',
        'notes',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'company_name' => $this->company_name,
            'contact_name' => $this->contact_name,
            'email' => $this->email,
        ];
    }

    /**
     * Get the licenses for this customer.
     */
    public function licenses()
    {
        return $this->hasMany(License::class);
    }

    /**
     * Scope a query to only include active customers.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to search customers by name or email.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('company_name', 'like', "%{$search}%")
                ->orWhere('contact_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        });
    }

    /**
     * Get the customer's full display name.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->company_name} ({$this->contact_name})";
    }

    /**
     * Get the number of active licenses for this customer.
     */
    public function getActiveLicenseCountAttribute(): int
    {
        return $this->licenses()->where('status', 'ACTIVE')->count();
    }

    /**
     * Get the total number of licenses for this customer.
     */
    public function getTotalLicenseCountAttribute(): int
    {
        return $this->licenses()->count();
    }

    /**
     * Check if customer has any active licenses.
     */
    public function hasActiveLicenses(): bool
    {
        return $this->licenses()->where('status', 'ACTIVE')->exists();
    }

    /**
     * Get all active licenses for this customer.
     */
    public function getActiveLicenses()
    {
        return $this->licenses()->where('status', 'ACTIVE')->get();
    }

    /**
     * Get all expired licenses for this customer.
     */
    public function getExpiredLicenses()
    {
        return $this->licenses()->where('status', 'EXPIRED')->get();
    }

    /**
     * Deactivate this customer.
     */
    public function deactivate(): bool
    {
        return $this->update(['is_active' => false]);
    }

    /**
     * Activate this customer.
     */
    public function activate(): bool
    {
        return $this->update(['is_active' => true]);
    }
}
