# Licenly - Secure Software Licensing Solution

A modern, secure, and offline-capable license management system built with Laravel 12 for generating and managing cryptographically signed software licenses using RSA-4096 encryption.

## 🔗 Client-Side Validation Package

**[Laravel PEM License Validator](https://github.com/Nazrulalif/laravel-pem-license-validator)** - Ready-to-use package for validating Licenly-generated licenses in your applications

## Features

-   🔐 **RSA-4096 Encryption** - Industry-standard cryptographic security
-   📄 **PEM License Files** - Standard format, offline validation
-   👥 **Customer Management** - Complete CRUD for customer data
-   📊 **Dashboard** - Real-time statistics and insights
-   🔑 **Multiple RSA Keys** - Support for key rotation
-   ⚡ **Offline Validation** - No internet required for license checks
-   🎨 **Modern UI** - Built with Metronic Bootstrap 5 template

## Tech Stack

-   **Framework:** Laravel 12
-   **Database:** SQLite
-   **Frontend:** Blade Templates + Bootstrap 5 (Metronic)
-   **Encryption:** phpseclib/phpseclib (RSA-4096)
-   **Storage:** Local filesystem

## System Architecture

```
┌─────────────────────────────────────┐
│    License Management System        │
├─────────────────────────────────────┤
│  Admin generates RSA key pair       │
│         ↓                           │
│  Admin creates customer             │
│         ↓                           │
│  Admin generates license            │
│         ↓                           │
│  System signs with private key      │
│         ↓                           │
│  .pem file generated                │
│         ↓                           │
│  Customer receives .pem file        │
│         ↓                           │
│  Customer validates with public key │
└─────────────────────────────────────┘
```

## Installation

### Requirements

-   PHP 8.2+
-   Composer
-   SQLite

### Setup

```bash
# Clone repository
git clone https://github.com/Nazrulalif/licenly.git
cd licenly

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database (already set for SQLite in .env)
touch database/database.sqlite

# Create storage link
php artisan storage:link

# Create storage directories
mkdir -p storage/indexes
chmod -R 775 storage

# Run migrations
php artisan migrate

# Create admin user
php artisan db:seed --class=UserSeeder

# Start development server
php artisan serve
```

Access at: `http://localhost:8000`

## Usage

### 1. Generate RSA Key Pair

Navigate to **RSA Keys** → Click **Generate New Key**

-   System generates 4096-bit RSA key pair
-   Private key encrypted with AES-256
-   Only one active key at a time

### 2. Add Customer

Navigate to **Customers** → Click **Add Customer**

Required fields:

-   Company Name
-   Contact Name
-   Email

### 3. Generate License

Navigate to **Licenses** → Click **Generate License**

Configure:

-   Customer selection
-   License type (Trial, Personal, Professional, Enterprise, Custom)
-   Issue & expiry dates
-   Max devices
-   Features (JSON format)
-   Hardware ID (optional)

Example features JSON:

```json
{
    "UserLimit": 10,
    "sso": true,
    "api_access": true,
    "storage_gb": 100,
    "priority_support": true
}
```

### 4. Download & Deliver

-   Click **Download** to get `.pem` file
-   Send to customer via email or secure transfer

## Database Schema

### Tables

-   **users** - Admin authentication
-   **rsa_keys** - RSA key pairs storage
-   **customers** - Customer information
-   **licenses** - License records with signatures

### Key Relationships

```
rsa_keys (1) ──→ (N) licenses
customers (1) ──→ (N) licenses
```

## License File Format

Generated `.pem` files contain:

```
-----BEGIN LICENSE-----
[Base64 encoded license JSON data]
-----END LICENSE-----
-----BEGIN LICENSE SIGNATURE-----
[Base64 encoded RSA-4096 signature]
-----END LICENSE SIGNATURE-----
```

## Customer Integration

Customers validate licenses using the provided `LicenseValidator.php` class:

[PEM License Validation](https://github.com/Nazrulalif/laravel-pem-license-validator)

```

## Security Features

-   ✅ Private keys encrypted with AES-256
-   ✅ RSA-4096 signatures (SHA-256 hash)
-   ✅ Tamper-proof license files
-   ✅ Offline validation capability
-   ✅ Hardware binding support (optional)
-   ✅ Secure key storage in database

## License Types

-   **TRIAL** - Time-limited evaluation
-   **PERSONAL** - Single user license
-   **PROFESSIONAL** - Multi-user license
-   **ENTERPRISE** - Organization-wide license
-   **CUSTOM** - Flexible custom licensing

## License Statuses

-   **ACTIVE** - Currently valid
-   **EXPIRED** - Past expiry date
-   **REVOKED** - Manually disabled
-   **PENDING** - Not yet activated

## Management Operations

### Extend License

-   Update expiry date
-   Regenerate signed `.pem` file
-   Status automatically reset to ACTIVE

### Revoke License

-   Mark as REVOKED
-   Record revocation reason
-   Customer validation fails

### Delete License

-   Only expired/revoked licenses
-   Removes database record
-   Deletes `.pem` file from storage

## File Structure

```

app/
├── Http/Controllers/
│ ├── DashboardController.php
│ ├── RsaKeyController.php
│ ├── CustomerController.php
│ └── LicenseController.php
├── Models/
│ ├── RsaKey.php
│ ├── Customer.php
│ └── License.php
└── Services/
├── RsaKeyService.php
└── LicenseService.php

resources/views/
├── dashboard/
├── rsa-keys/
├── customers/
└── licenses/

storage/app/licenses/
└── \*.pem files

````

## Environment Variables

```env
APP_NAME="License Management System"
APP_ENV=production
APP_KEY=base64:...

DB_CONNECTION=sqlite

# Auto-configured by Laravel
ENCRYPTION_KEY=...
````

## Development

```bash
# Run migrations
php artisan migrate

# Fresh database with seeder
php artisan migrate:fresh

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## Production Deployment

1. Set `APP_ENV=production` in `.env`
2. Set `APP_DEBUG=false`
3. Run `php artisan config:cache`
4. Run `php artisan route:cache`
5. Set proper file permissions on `storage/` and `bootstrap/cache/`
6. Configure web server (Nginx/Apache)

## Troubleshooting

**Issue:** RSA key generation fails

-   Ensure `phpseclib/phpseclib` is installed
-   Check PHP memory limit (increase to 256M+)

**Issue:** License validation fails

-   Verify public key matches in validator
-   Check license file integrity
-   Ensure expiry date not passed

**Issue:** Cannot download .pem file

-   Check `storage/app/licenses/` directory exists
-   Verify file permissions (755 for directories, 644 for files)

## Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push to branch (`git push origin feature/amazing-feature`)
5. Open Pull Request

## Acknowledgments

-   Laravel Framework
-   phpseclib for cryptography
-   Metronic Bootstrap template

---

**Built with ❤️ using Laravel 12**
