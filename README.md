# Filament Settings

This package adds a settings page to the Filament admin panel.

## Installation

Because this package is not published to Packagist, you need to add it as a repository in your `composer.json` file:

```json
"repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/VanOns/filament-settings"
    }
]
```

Then, require the package:

```bash
composer require van-ons/filament-settings
```

### Customizing the config

To publish the config
file, run the following command:

```bash
php artisan vendor:publish --tag=filament-settings-config
```

### Customizing the language files

If you want to customize the language files, you can publish them by running the following command:

```bash
php artisan vendor:publish --tag=filament-settings-lang
```

## Usage

...