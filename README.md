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

### Application Flow

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

### Docker Architecture

**Development Environment:**
```
┌──────────────────────────────────────────────┐
│                Docker Host                    │
├──────────────────────────────────────────────┤
│  ┌─────────────┐  ┌──────────────┐           │
│  │   Nginx     │  │  PHP-FPM     │           │
│  │   :8000     │→ │  (Laravel)   │           │
│  │             │  │              │           │
│  └─────────────┘  └──────┬───────┘           │
│                          │                    │
│  ┌─────────────┐  ┌──────▼───────┐           │
│  │   Node.js   │  │   SQLite     │           │
│  │  (Vite)     │  │   Database   │           │
│  │   :5173     │  │              │           │
│  └─────────────┘  └──────────────┘           │
│                                               │
│  ┌─────────────┐                              │
│  │   Redis     │  Shared volumes:             │
│  │   :6379     │  - ./:/var/www (code)        │
│  └─────────────┘  - storage (persistent)      │
└──────────────────────────────────────────────┘
```

**Production Environment:**
```
┌──────────────────────────────────────────────┐
│                Docker Host                    │
├──────────────────────────────────────────────┤
│  ┌─────────────┐  ┌──────────────┐           │
│  │   Nginx     │  │  PHP-FPM     │           │
│  │   :80       │→ │  (Laravel)   │           │
│  │  (optimized)│  │  (prod mode) │           │
│  └─────────────┘  └──────┬───────┘           │
│                          │                    │
│  ┌─────────────┐  ┌──────▼───────┐           │
│  │   Redis     │  │   SQLite/    │           │
│  │  (cache)    │  │  PostgreSQL  │           │
│  │             │  │              │           │
│  └─────────────┘  └──────────────┘           │
│                                               │
│  Named volumes (persistent):                 │
│  - laravel-storage-production                │
│  - sqlite-data-production                    │
└──────────────────────────────────────────────┘
```

## Installation

### Requirements

-   PHP 8.2+
-   Composer
-   SQLite

### Option 1: Docker Setup (Recommended)

Docker provides an isolated, consistent environment for both development and production.

#### Quick Start

```bash
# Clone repository
git clone https://github.com/Nazrulalif/licenly.git
cd licenly

# Copy environment file
cp .env.example .env

# Start development environment
make dev-build

# Or manually:
docker compose up -d --build

# Run initial setup (migrations and seeding)
make setup

# Or manually:
docker compose exec php-fpm php artisan key:generate
docker compose exec php-fpm php artisan migrate --force
docker compose exec php-fpm php artisan db:seed --class=UserSeeder --force
docker compose exec php-fpm php artisan storage:link
```

**Access at:** `http://localhost:8000`

**Default Accounts:**
- Admin: `admin@yahoo.com` / `password`
- User: `user@example.com` / `password`
- Demo: `demo@example.com` / `demo`

#### Docker Commands

**Development Environment:**
```bash
# Start all services
make dev                          # or: docker compose up -d

# Build and start
make dev-build                    # or: docker compose up -d --build

# Stop all services
make dev-stop                     # or: docker compose down

# Restart services
make dev-restart                  # or: docker compose restart

# View logs
make logs                         # or: docker compose logs -f
make logs-php                     # PHP-FPM logs only
make logs-web                     # Nginx logs only
make logs-laravel                 # Laravel application logs
```

**Production Environment:**
```bash
# Start production
make prod-build                   # or: docker compose -f compose.prod.yaml up -d --build

# Stop production
make prod-stop                    # or: docker compose -f compose.prod.yaml down

# View production logs
docker compose -f compose.prod.yaml logs -f
```

**Database Operations:**
```bash
# Run migrations
make migrate                      # or: docker compose exec php-fpm php artisan migrate --force

# Seed database
make seed                         # or: docker compose exec php-fpm php artisan db:seed --class=UserSeeder --force

# Fresh migration (drops all tables)
make migrate-fresh                # or: docker compose exec php-fpm php artisan migrate:fresh --force

# Reset database (fresh + seed)
make db-reset                     # or: docker compose exec php-fpm php artisan migrate:fresh --seed --force
```

**Cache Management:**
```bash
# Clear all caches
make cache-clear                  # Clears config, route, view, and application cache

# Optimize for production
make cache-optimize               # Cache config, routes, and views
```

**Container Access:**
```bash
# Access PHP container shell
make shell                        # or: docker compose exec php-fpm sh

# Access Nginx container
make shell-web                    # or: docker compose exec web sh

# Open Laravel Tinker
make tinker                       # or: docker compose exec php-fpm php artisan tinker
```

**Dependency Management:**
```bash
# Install Composer dependencies
make composer-install             # or: docker compose exec php-fpm composer install

# Update Composer dependencies
make composer-update              # or: docker compose exec php-fpm composer update

# Install NPM dependencies
make npm-install                  # or: docker compose exec node npm install

# Build frontend assets
make npm-build                    # or: docker compose exec node npm run build

# Start Vite dev server
make npm-dev                      # or: docker compose exec node npm run dev
```

**Backup & Restore:**
```bash
# Backup SQLite database
make backup-db                    # Creates backup in ./backups/

# Backup storage files
make backup-storage               # Creates backup in ./backups/
```

**Testing:**
```bash
# Run PHPUnit tests
make test                         # or: docker compose exec php-fpm php artisan test
```

**Queue Management:**
```bash
# Start queue worker
make queue-work                   # or: docker compose exec php-fpm php artisan queue:work

# Restart queue workers
make queue-restart                # or: docker compose exec php-fpm php artisan queue:restart
```

**Cleanup:**
```bash
# View running containers
make ps                           # or: docker compose ps

# Restart all containers
make restart                      # or: docker compose restart

# Remove all containers and volumes
make clean                        # WARNING: Destroys all data!
```

**Help:**
```bash
# Show all available commands
make help
```

#### Docker Environment Details

**Development Stack:**
- Nginx Alpine (port 8000)
- PHP 8.4-FPM with development tools (Xdebug, etc.)
- Redis Alpine (port 6379)
- Node.js 20 Alpine (Vite dev server on port 5173)
- SQLite (optional PostgreSQL available)

**Production Stack:**
- Nginx Alpine with optimizations (port 80)
- PHP 8.4-FPM production-optimized
- Redis for caching
- SQLite (optional PostgreSQL available)
- Optional queue worker (commented out, ready to enable)

**Volumes:**
- `laravel-storage-production`: Persistent storage for uploaded files
- `sqlite-data-production`: Database persistence
- `postgres-data-production`: PostgreSQL data (if enabled)

#### Switching to PostgreSQL

To use PostgreSQL instead of SQLite in Docker:

1. Edit `.env`:
```bash
DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=license
DB_USERNAME=license
DB_PASSWORD=your_secure_password
```

2. Uncomment PostgreSQL service in `docker-compose.yml` or `compose.prod.yaml`

3. Rebuild and restart:
```bash
make dev-build    # or make prod-build for production
make migrate
make seed
```

### Option 2: Local Setup (Traditional)

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

**Access at:** `http://localhost:8000`

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

### Docker Production Deployment (Recommended)

```bash
# 1. Configure environment
cp .env.example .env
# Edit .env and set:
# APP_ENV=production
# APP_DEBUG=false
# DB_CONNECTION=sqlite (or pgsql)

# 2. Build and start production containers
make prod-build
# or: docker compose -f compose.prod.yaml up -d --build

# 3. Generate application key
docker compose -f compose.prod.yaml exec php-fpm php artisan key:generate --force

# 4. Run migrations and seed
docker compose -f compose.prod.yaml exec php-fpm php artisan migrate --force
docker compose -f compose.prod.yaml exec php-fpm php artisan db:seed --class=UserSeeder --force

# 5. Optimize for production
docker compose -f compose.prod.yaml exec php-fpm php artisan config:cache
docker compose -f compose.prod.yaml exec php-fpm php artisan route:cache
docker compose -f compose.prod.yaml exec php-fpm php artisan view:cache

# 6. Create storage link
docker compose -f compose.prod.yaml exec php-fpm php artisan storage:link
```

**Access at:** `http://localhost` (port 80)

### Traditional Production Deployment

1. Set `APP_ENV=production` in `.env`
2. Set `APP_DEBUG=false`
3. Run `php artisan config:cache`
4. Run `php artisan route:cache`
5. Run `php artisan view:cache`
6. Set proper file permissions on `storage/` and `bootstrap/cache/`
7. Configure web server (Nginx/Apache)

## Troubleshooting

### Docker Issues

**Issue:** Port already in use (8000 or 80)

```bash
# Check what's using the port
lsof -i :8000  # or :80 for production

# Change port in .env
NGINX_PORT=8001

# Restart containers
make dev-restart
```

**Issue:** Permission denied errors in Docker

```bash
# Fix permissions
docker compose exec php-fpm chown -R www-data:www-data /var/www/storage
docker compose exec php-fpm chmod -R 775 /var/www/storage
```

**Issue:** View not found (case sensitivity)

Docker uses Linux filesystem (case-sensitive), while macOS/Windows are case-insensitive. Ensure view paths match exactly:

```bash
# Wrong:
return view('pages.rsakey.index');  // lowercase 'k'

# Correct:
return view('pages.rsaKey.index');  // uppercase 'K' matches directory name
```

**Issue:** Database seeder class not found

```bash
# Use correct class name with --class flag
docker compose exec php-fpm php artisan db:seed --class=UserSeeder
```

**Issue:** Changes not reflected in production container

Production images have code baked in. After code changes:

```bash
# Rebuild the container
make prod-build
# or: docker compose -f compose.prod.yaml up -d --build php-fpm

# Clear caches
docker compose -f compose.prod.yaml exec php-fpm php artisan cache:clear
docker compose -f compose.prod.yaml exec php-fpm php artisan view:clear
```

**Issue:** Cannot connect to database

```bash
# Check if containers are running
docker compose ps

# Check logs
docker compose logs php-fpm

# Verify database connection in .env
DB_CONNECTION=sqlite
DB_DATABASE=/var/www/database/database.sqlite
```

**Issue:** Composer/NPM dependencies not installing

```bash
# Force reinstall
docker compose exec php-fpm composer install --no-cache
docker compose exec node npm install --force
```

### General Issues

**Issue:** RSA key generation fails

-   Ensure `phpseclib/phpseclib` is installed
-   Check PHP memory limit (increase to 256M+)
-   In Docker: `docker compose exec php-fpm php -i | grep memory_limit`

**Issue:** License validation fails

-   Verify public key matches in validator
-   Check license file integrity
-   Ensure expiry date not passed

**Issue:** Cannot download .pem file

-   Check `storage/app/licenses/` directory exists
-   Verify file permissions (755 for directories, 644 for files)
-   In Docker: `docker compose exec php-fpm ls -la /var/www/storage/app/`

**Issue:** 500 Internal Server Error

```bash
# Check Laravel logs
docker compose logs php-fpm | tail -100
# or
docker compose exec php-fpm tail -f /var/www/storage/logs/laravel.log

# Check Nginx error logs
docker compose logs web | tail -100
```

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
