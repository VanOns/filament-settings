<p align="center"><img src="art/social-card.png" alt="Social card of Filament Settings"></p>

# Filament Settings

[![Latest version on GitHub](https://img.shields.io/github/release/VanOns/filament-settings.svg?style=flat-square)](https://github.com/VanOns/filament-settings/releases)
[![Total downloads](https://img.shields.io/packagist/dt/van-ons/filament-settings.svg?style=flat-square)](https://packagist.org/packages/van-ons/filament-settings)
[![GitHub issues](https://img.shields.io/github/issues/VanOns/filament-settings?style=flat-square)](https://github.com/VanOns/filament-settings/issues)
[![License](https://img.shields.io/github/license/VanOns/filament-settings?style=flat-square)](https://github.com/VanOns/filament-settings/blob/main/LICENSE.md)

Add easy-to-use settings pages to your Filament admin panel.

## Quick start

> For Filament version compatibility, see [Compatibility](docs/compatibility.md).

### Installation

You can install the package via Composer:

```bash
composer require van-ons/filament-settings:^3.0
```

### Create a settings page

```bash
php artisan make:filament-settings-page GeneralSettings
```

This creates `GeneralSettingsPage.php` (the form) and `GeneralSettings.php` (the defaults).

### Read and write settings

```php
use VanOns\FilamentSettings\Facades\FilamentSettings;

FilamentSettings::getValue('general');
FilamentSettings::setValue('general', ['site_name' => 'My App']);
```

## Customization

### Language files

If you want to customize the language files, you can publish them by running the following command:

```bash
php artisan vendor:publish --tag=filament-settings-lang
```

## Documentation

Please see the [documentation](docs) for detailed information about installation and usage.

## Contributing

Please see [Contributing](CONTRIBUTING.md) for more information about how you can contribute.

## Testing

```bash
composer test
```

## Changelog

Please see [Changelog](CHANGELOG.md) for more information about what has changed recently.

## Upgrading

Please see [Upgrading](UPGRADING.md) for more information about how to upgrade.

## Security

Please see [Security](SECURITY.md) for more information about how we deal with security.

## Credits

We would like to thank the following contributors for their contributions to this project:

- [All contributors](../../contributors)

## License

The scripts and documentation in this project are released under the [MIT License](LICENSE.md).

---

<p align="center"><a href="https://van-ons.nl/" target="_blank"><img src="https://opensource.van-ons.nl/files/cow.png" width="50" alt="Logo of Van Ons"></a></p>

