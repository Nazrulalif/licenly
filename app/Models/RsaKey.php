<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;

class RsaKey extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'public_key',
        'private_key',
        'key_size',
        'is_active',
        'generated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'generated_at' => 'datetime',
        'key_size' => 'integer',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'private_key',
    ];

    /**
     * Boot the model.
     */
    protected static function booted(): void
    {
        // When activating a key, deactivate all others
        static::updating(function ($rsaKey) {
            if ($rsaKey->isDirty('is_active') && $rsaKey->is_active) {
                static::where('id', '!=', $rsaKey->id)
                    ->update(['is_active' => false]);
            }
        });

        // When creating an active key, deactivate all others
        static::creating(function ($rsaKey) {
            if ($rsaKey->is_active) {
                static::query()->update(['is_active' => false]);
            }
        });
    }

    /**
     * Get the licenses associated with this RSA key.
     */
    public function licenses()
    {
        return $this->hasMany(License::class);
    }

    /**
     * Scope a query to only include active keys.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the encrypted private key.
     */
    public function getEncryptedPrivateKeyAttribute(): string
    {
        return $this->private_key;
    }

    /**
     * Get the decrypted private key.
     */
    public function getDecryptedPrivateKeyAttribute(): string
    {
        return Crypt::decryptString($this->private_key);
    }

    /**
     * Generate a display name for the key.
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->name ?: 'RSA Key - ' . $this->generated_at->format('M d, Y');
    }

    /**
     * Check if this is the active key.
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Activate this key (deactivates all others).
     */
    public function activate(): bool
    {
        return $this->update(['is_active' => true]);
    }

    /**
     * Deactivate this key.
     */
    public function deactivate(): bool
    {
        return $this->update(['is_active' => false]);
    }

    /**
     * Get the number of licenses using this key.
     */
    public function getLicenseCountAttribute(): int
    {
        return $this->licenses()->count();
    }
}
