---
name: filament-settings-page
description: Scaffold and wire up a Filament settings page using van-ons/filament-settings. Use when the user asks to add a settings page, settings screen, configuration page, admin preferences UI, or to run/extend `make:filament-settings-page`. Covers form schema, defaults, persisting nested values, mutators, and reading settings from app code.
---

# Filament settings page

## When to use this skill

- "Add a settings page for X" / "Create an admin preferences screen"
- User asks to run `php artisan make:filament-settings-page` or extend its output
- Reading or writing settings persisted by `van-ons/filament-settings` from non-Filament code
- Adding new fields to an existing settings page (defaults, casting, mutation)

## Workflow

### 1. Scaffold

```bash
php artisan make:filament-settings-page GeneralSettings
```

Produces:

| File | Purpose |
|---|---|
| `app/Filament/Pages/GeneralSettingsPage.php` | Filament page. Form definition. |
| `app/Settings/GeneralSettings.php` | Data class. Defaults + `$settingsName`. |

The command argument should end in `Settings` by convention. The page suffix `Page` is appended automatically.

**Storage key gotcha:** the generated `$settingsName` = first kebab segment of the class name. `EmailNotificationSettings` → `'email'`. If you have multiple settings groups whose class names share a first word, override `$settingsName` manually on the data class.

### 2. Define defaults on the data class

```php
namespace App\Settings;

use VanOns\FilamentSettings\Classes\Settings;

class GeneralSettings extends Settings
{
    public string $settingsName = 'general';

    public function defaults(): array
    {
        return [
            'site' => ['title' => config('app.name'), 'tagline' => null],
            'features' => ['signups_open' => true],
        ];
    }
}
```

`defaults()` is **only consulted when no DB row exists yet** for `$settingsName`. After the first save, the stored array replaces it wholesale — defaults are not merged in. When adding new keys later, either backfill the row or guard reads with `??`.

### 3. Build the form schema on the page

```php
namespace App\Filament\Pages;

use App\Settings\GeneralSettings;
use Filament\Forms\Components\{TextInput, Toggle};
use Filament\Schemas\Components\Section;
use VanOns\FilamentSettings\Filament\Pages\SettingsPage;

class GeneralSettingsPage extends SettingsPage
{
    protected static ?string $title = 'General';
    protected string $settingsClass = GeneralSettings::class;

    public function getFormSchema(): array
    {
        return [
            Section::make('Site')->columnSpanFull()->schema([
                TextInput::make('site.title')->required(),
                TextInput::make('site.tagline'),
            ]),
            Section::make('Features')->columnSpanFull()->schema([
                Toggle::make('features.signups_open'),
            ]),
        ];
    }
}
```

Notes:

- The base page already calls `->statePath('settings')` and `->columns(3)`. Override `form(Schema $schema)` if you need different columns or state path.
- Component names use Filament's standard dot notation for nested arrays. They map to keys inside the JSON `value` blob.
- Navigation icon defaults to `heroicon-o-cog-6-tooth`. Override `protected static string|\BackedEnum|null $navigationIcon` to change.

### 4. Mutate data on fill / save (optional)

Override on the page class:

```php
public function mutateFormDataBeforeFill(array $data): array
{
    $data['secret_token'] = decrypt($data['secret_token'] ?? null);
    return $data;
}

public function mutateFormDataBeforeSave(array $data): array
{
    if (isset($data['secret_token'])) {
        $data['secret_token'] = encrypt($data['secret_token']);
    }
    return $data;
}
```

Use this for: encryption, type coercion, derived fields, stripping empties. The model casts `value` as `json` for the entire row — no per-key casts available.

### 5. Read settings from application code

```php
use App\Settings\GeneralSettings;

$settings = new GeneralSettings();
$title = $settings->get('site.title');     // dot notation supported
$all = $settings->get();                   // full array

// One-off write (also persists immediately):
$settings->set('features.signups_open', false);
```

Or hit the model directly:

```php
use VanOns\FilamentSettings\Models\Settings;

$value = Settings::getValue('general');         // returns the decoded array
Settings::set('general', ['site' => ['title' => 'New']]);  // overwrites the whole row
```

`Settings::set($name, $value)` does `updateOrCreate` on `name`. Calling it overwrites the entire stored array — read first, merge, then write if you want partial updates.

### 6. Store the group once per tenant / country / language (optional)

When the same page has to be filled in separately per tenant, country, language or brand, override
`getSettingsVariant()` on the page. Do **not** override `getSettingsInstance()` or build the key by hand.

```php
use Filament\Facades\Filament;

class GeneralSettingsPage extends SettingsPage
{
    protected string $settingsClass = GeneralSettings::class;

    public function getSettingsVariant(): ?string
    {
        return Filament::getTenant()?->code;
    }
}
```

The variant is appended to the settings name: `general` → `general.nl`, `general.de`. Returning `null` keeps
the plain `general` key, so pages that don't need this are unaffected.

Read a specific variant from application code:

```php
(new GeneralSettings())->withVariant('nl')->get('site.title');
```

Each variant falls back to `defaults()` until saved for the first time, and variants never read each other's
values. Compose more than one dimension by building the string yourself (`"{$country}.{$language}"`).

`withVariant()` re-reads from the database, so hold the instance instead of calling it per lookup.

## Common follow-ups

- **Multiple settings groups:** scaffold once per group (e.g. `make:filament-settings-page MailSettings`). Each group is one row in `settings`.
- **Custom save action:** the page returns `SaveAction::make()` from `getActions()`. Override `getActions()` on the page to add more actions.
- **Validation:** validation lives on form components (`->required()`, `->rules([...])`). The page's `submit()` catches `ValidationException` and dispatches `form-validation-error` to Livewire.
- **Authorization:** `SettingsPage` extends `Filament\Pages\Page`. Apply `static::canAccess()` / Filament policy hooks as you would on any page.
- **Localization:** publish `filament-settings-lang` to override messages: `php artisan vendor:publish --tag=filament-settings-lang`.

## Anti-patterns

- Don't merge `defaults()` into existing rows manually thinking the trait does it — it doesn't. After first save, only stored keys exist.
- Don't call `Settings::set($name, $partial)` expecting a deep merge. It's `updateOrCreate` with the full payload.
- Don't add fields to the form without also adding them to `defaults()` (or guarding reads) — old rows won't have the key.
- Don't try to cast individual keys via Eloquent `$casts` on `Settings` — only the whole `value` is JSON. Use mutators or accessors in the data class instead.
- Don't repurpose the empty `VanOns\FilamentSettings\FilamentSettings` class or its facade — they currently expose no public methods.
- Don't hand-roll per-tenant keys by overriding `getSettingsInstance()` or passing a composed `$settingsName` — use `getSettingsVariant()` / `forVariant()`.
