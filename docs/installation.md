# Installation Guide

This guide will walk you through installing the Manta Gallery Form package.

## Requirements

- PHP ^8.2
- Laravel ^12.0
- darvis/manta-laravel-flux-cms

## Installation Methods

### 1. Install Package

```bash
composer require darvis/manta-gallery
```

### 2. Automatic Installation (Recommended)

The easiest way to install the module is via the built-in install command:

```bash
php artisan manta-gallery:install
```

This command does the following:
- Publishes the configuration files
- Publishes the database migrations
- Asks if migrations should be run immediately
- Shows installation instructions and next steps

### 3. Manual Installation (Alternative)

If you want to perform the installation step by step:

```bash
# Publish configuration file
php artisan vendor:publish --tag=manta-gallery-config

# Publish database migrations
php artisan vendor:publish --tag=manta-gallery-migrations
```

### 4. Run Database Migrations

```bash
php artisan migrate
```

## Verification

After installation, you can verify the package is working by:

1. Checking if the routes are registered:
   ```bash
   php artisan route:list | grep gallery
   ```

2. Verifying the configuration file exists:
   ```bash
   ls config/manta-gallery.php
   ```

3. Checking if migrations ran successfully:
   ```bash
   php artisan migrate:status
   ```

## Next Steps

- [Configure the package](configuration.md)
- [Learn about usage](usage.md)
- [Understand the database schema](database.md)
