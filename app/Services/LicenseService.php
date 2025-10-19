<?php

namespace App\Services;

use App\Models\License;
use App\Models\Customer;
use App\Models\RsaKey;
use phpseclib3\Crypt\RSA;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Exception;

class LicenseService
{
    /**
     * Generate product key (5 groups of 5 characters)
     */
    public function generateProductKey(): string
    {
        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $groups = [];

        for ($i = 0; $i < 5; $i++) {
            $group = '';
            for ($j = 0; $j < 5; $j++) {
                $group .= $characters[random_int(0, strlen($characters) - 1)];
            }
            $groups[] = $group;
        }

        return implode('-', $groups);
    }

    /**
     * Generate unique license ID
     */
    public function generateLicenseId(): string
    {
        return 'LIC-' . time() . '-' . random_int(1000, 9999);
    }

    /**
     * Create license with signature
     */
    public function createLicense(array $data): License
    {
        try {
            // Get active RSA key
            $rsaKey = RsaKey::where('is_active', true)->firstOrFail();

            // Generate unique identifiers
            $licenseId = $this->generateLicenseId();
            $productKey = $this->generateProductKey();

            // Get customer
            $customer = Customer::findOrFail($data['customer_id']);

            // Prepare license data
            $licenseData = [
                'productKey' => $productKey,
                'licenseId' => $licenseId,
                'licenseType' => $data['license_type'],
                'companyName' => $customer->company_name,
                'email' => $customer->email,
                'maxDevices' => (int) $data['max_devices'],
                'features' => json_decode($data['features'], true),
                'hardwareId' => $data['hardware_id'] ?? null,
                'issueDate' => $data['issue_date'],
                'expiryDate' => $data['expiry_date'],
            ];

            // Sign license
            $signature = $this->signLicense($licenseData, $rsaKey);

            // Create PEM content
            $pemContent = $this->createPemFile($licenseData, $signature);

            // Save to storage
            $filename = $licenseId . '.pem';
            Storage::put('licenses/' . $filename, $pemContent);

            // Create license record
            $license = License::create([
                'license_id' => $licenseId,
                'product_key' => $productKey,
                'customer_id' => $data['customer_id'],
                'rsa_key_id' => $rsaKey->id,
                'license_type' => $data['license_type'],
                'status' => 'ACTIVE',
                'max_devices' => $data['max_devices'],
                'features' => $data['features'],
                'hardware_id' => $data['hardware_id'] ?? null,
                'issue_date' => $data['issue_date'],
                'expiry_date' => $data['expiry_date'],
                'pem_content' => $pemContent,
                'license_data' => json_encode($licenseData),
                'signature' => $signature,
            ]);

            return $license;
        } catch (Exception $e) {
            throw new Exception('Failed to create license: ' . $e->getMessage());
        }
    }

    /**
     * Sign license data with RSA private key
     */
    protected function signLicense(array $licenseData, RsaKey $rsaKey): string
    {
        try {
            // Get decrypted private key
            $privateKeyPEM = Crypt::decryptString($rsaKey->private_key);

            // Load private key
            $privateKey = RSA::loadPrivateKey($privateKeyPEM);

            // Convert license data to JSON
            $jsonData = json_encode($licenseData, JSON_PRETTY_PRINT);

            // Sign with SHA-256
            $signature = $privateKey
                ->withHash('sha256')
                ->withPadding(RSA::SIGNATURE_PKCS1)
                ->sign($jsonData);

            // Return base64 encoded signature
            return base64_encode($signature);
        } catch (Exception $e) {
            throw new Exception('Failed to sign license: ' . $e->getMessage());
        }
    }

    /**
     * Create PEM format file
     */
    protected function createPemFile(array $licenseData, string $signature): string
    {
        $jsonData = json_encode($licenseData, JSON_PRETTY_PRINT);
        $base64Data = base64_encode($jsonData);

        // Format base64 data into 64-character lines
        $formattedData = chunk_split($base64Data, 64, "\n");

        // Format signature into 64-character lines
        $formattedSignature = chunk_split($signature, 64, "\n");

        $pem = "-----BEGIN LICENSE-----\n";
        $pem .= $formattedData;
        $pem .= "-----END LICENSE-----\n";
        $pem .= "-----BEGIN LICENSE SIGNATURE-----\n";
        $pem .= $formattedSignature;
        $pem .= "-----END LICENSE SIGNATURE-----\n";

        return $pem;
    }

    /**
     * Extend license expiry date
     */
    public function extendLicense(License $license, string $newExpiryDate): License
    {
        try {
            // Update license data
            $licenseData = json_decode($license->license_data, true);
            $licenseData['expiryDate'] = $newExpiryDate;

            // Re-sign license
            $signature = $this->signLicense($licenseData, $license->rsaKey);

            // Create new PEM content
            $pemContent = $this->createPemFile($licenseData, $signature);

            // Update storage
            $filename = $license->license_id . '.pem';
            Storage::put('licenses/' . $filename, $pemContent);

            // Update license record
            $license->update([
                'expiry_date' => $newExpiryDate,
                'pem_content' => $pemContent,
                'license_data' => json_encode($licenseData),
                'signature' => $signature,
                'status' => 'ACTIVE',
            ]);

            return $license->fresh();
        } catch (Exception $e) {
            throw new Exception('Failed to extend license: ' . $e->getMessage());
        }
    }

    /**
     * Revoke license
     */
    public function revokeLicense(License $license, ?string $reason = null): bool
    {
        return $license->update([
            'status' => 'REVOKED',
            'revoked_at' => now(),
            'revocation_reason' => $reason,
        ]);
    }

    /**
     * Check and update expired licenses
     */
    public function updateExpiredLicenses(): int
    {
        return License::where('status', 'ACTIVE')
            ->where('expiry_date', '<', now())
            ->update(['status' => 'EXPIRED']);
    }
}
