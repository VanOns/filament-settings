# Basic usage

You can create a new setting page by running the following command:

```bash
php artisan make:filament-settings-page GeneralSettings
```

Executing this command will create two files:

- `app/Filament/Pages/GeneralSettingsPage.php`: The settings page class.
- `app/Settings/GeneralSettings.php`: The settings class.

To modify the form, go to `GeneralSettingsPage`.
To modify the default values, go to `GeneralSettings`.

### Reading and writing settings

Use the `FilamentSettings` facade to get and set values anywhere in your application:

```php
use VanOns\FilamentSettings\Facade\FilamentSettings;

// Get a setting value
$value = FilamentSettings::getValue('general');
$value = FilamentSettings::getValue('general', 'default');

// Set a setting value
FilamentSettings::setValue('general', ['site_name' => 'My App']);
```

> **Note:** The key corresponds to the `$settingsName` property on your settings page class.
