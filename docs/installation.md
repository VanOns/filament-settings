# Installation

You can install the package via Composer:

```bash
composer require van-ons/filament-settings:^3.0
```

Then publish and run the migration that creates the `settings` table:

```bash
php artisan vendor:publish --tag=filament-settings-migrations
php artisan migrate
```

After an update, publish again to get any new migrations: the ones you already have are skipped.
