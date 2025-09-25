# Filament Settings

This package provides an easy-to-use settings page for Filament.

## Quick start

### Compatibility

For certain Filament versions, changes have to be made that render the package backwards incompatible with the previous version.
Please see the table below to determine which version you need.

| Version                                                            | Filament |
|--------------------------------------------------------------------|----------|
| v2 (current)                                                       | \>=4.0   |
| [v1](https://github.com/VanOns/filament-settings/tree/releases/v1) | <4.0     |

**Please note:** the `main` branch will always be the latest major version.

### Installation

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
composer require van-ons/filament-settings:^2.0
```

## Usage
You can create a new setting page by running the following command:

```bash
php artisan make:filament-settings-page GeneralSettings
```

Executing this command will create two files:
- `app/Filament/Pages/GeneralSettingsPage.php`: The settings page class.
- `app/Settings/GeneralSettings.php`: The settings class.

To modify the form, go to `GeneralSettingsPage`.
To modify the default values, go to `GeneralSettings`.

## Customizing the language files

If you want to customize the language files, you can publish them by running the following command:

```bash
php artisan vendor:publish --tag=filament-settings-lang
```
