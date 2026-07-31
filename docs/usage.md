# Usage

You can create a new setting page by running the following command:

```bash
php artisan make:filament-settings-page GeneralSettings
```

Executing this command will create two files:

- `app/Filament/Pages/GeneralSettingsPage.php`: The settings page class.
- `app/Settings/GeneralSettings.php`: The settings class.

To modify the form, go to `GeneralSettingsPage`.
To modify the default values, go to `GeneralSettings`.

## Reading and writing settings

Use the `FilamentSettings` facade to get and set values anywhere in your application:

```php
use VanOns\FilamentSettings\Facades\FilamentSettings;

// Get a setting value
$value = FilamentSettings::getValue('general');
$value = FilamentSettings::getValue('general', 'default');

// Set a setting value
FilamentSettings::setValue('general', ['site_name' => 'My App']);
```

> **Note:** The key corresponds to the `$settingsName` property on your settings page class.

## Variants

A variant stores the same settings group more than once, under its own key. Use it when one page has to be
filled in per tenant, per country, per language, or per brand.

Override `getSettingsVariant()` on the page to say which variant the current request is editing:

```php
use Filament\Facades\Filament;
use VanOns\FilamentSettings\Filament\Pages\SettingsPage;

class GeneralSettingsPage extends SettingsPage
{
    protected string $settingsClass = GeneralSettings::class;

    public function getSettingsVariant(): ?string
    {
        return Filament::getTenant()?->code;
    }
}
```

The variant is appended to the settings name, so `general` becomes `general.nl` and `general.de` — separate
rows, edited through the same page. Returning `null` (the default) keeps the plain `general` key, so pages
that don't use variants behave exactly as before.

Read a specific variant from anywhere with `withVariant()`:

```php
(new GeneralSettings())->withVariant('nl')->get('site.title');
(new GeneralSettings())->get('site.title'); // the unscoped row
```

Each variant falls back to `defaults()` until it is saved for the first time, and variants never read each
other's values.

> **Note:** `withVariant()` re-reads the settings, so building a variant instance costs one extra query. Call
> it once and reuse the instance rather than chaining it per lookup.

## Customization

### Language files

If you want to customize the language files, you can publish them by running the following command:

```bash
php artisan vendor:publish --tag=filament-settings-lang
```
