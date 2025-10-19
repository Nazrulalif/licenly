<?php

namespace App\Services;

use App\Models\RsaKey;
use phpseclib3\Crypt\RSA;
use Illuminate\Support\Facades\Crypt;
use Exception;

class RsaKeyService
{
    /**
     * Generate new RSA key pair (4096-bit)
     */
    public function generateKeyPair(): RsaKey
    {
        try {
            // Generate RSA key pair
            $privateKey = RSA::createKey(4096);
            $publicKey = $privateKey->getPublicKey();

            // Get PEM format
            $privateKeyPEM = $privateKey->toString('PKCS8');
            $publicKeyPEM = $publicKey->toString('PKCS8');

            // Encrypt private key with Laravel's encryption
            $encryptedPrivateKey = Crypt::encryptString($privateKeyPEM);

            // Auto-generate name
            $name = 'RSA Key - ' . now()->format('M d, Y');

            // Deactivate all existing keys
            RsaKey::query()->update(['is_active' => false]);

            // Create new key
            $rsaKey = RsaKey::create([
                'name' => $name,
                'public_key' => $publicKeyPEM,
                'private_key' => $encryptedPrivateKey,
                'key_size' => 4096,
                'is_active' => true,
                'generated_at' => now(),
            ]);

            return $rsaKey;

        } catch (Exception $e) {
            throw new Exception('Failed to generate RSA key pair: ' . $e->getMessage());
        }
    }

    /**
     * Get decrypted private key
     */
    public function getDecryptedPrivateKey(RsaKey $rsaKey): string
    {
        try {
            return Crypt::decryptString($rsaKey->private_key);
        } catch (Exception $e) {
            throw new Exception('Failed to decrypt private key: ' . $e->getMessage());
        }
    }

    /**
     * Set key as active (deactivate others)
     */
    public function setActive(RsaKey $rsaKey): bool
    {
        RsaKey::query()->update(['is_active' => false]);
        return $rsaKey->update(['is_active' => true]);
    }

    /**
     * Get active key
     */
    public function getActiveKey(): ?RsaKey
    {
        return RsaKey::where('is_active', true)->first();
    }

    /**
     * Validate key can be deleted
     */
    public function canDelete(RsaKey $rsaKey): bool
    {
        // Cannot delete if it has licenses
        if ($rsaKey->licenses()->count() > 0) {
            return false;
        }

        // Cannot delete if it's the only key
        if (RsaKey::count() <= 1) {
            return false;
        }

        return true;
    }
}
