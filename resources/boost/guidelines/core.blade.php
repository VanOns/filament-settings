## Filament Settings (`van-ons/filament-settings`)

Provides an abstract Filament `Page` plus a backing data class for building admin settings pages. Values are persisted as JSON in a single `settings` table (one row per settings group, keyed by `name`).

### Setup

- Service provider auto-discovered: `VanOns\FilamentSettings\FilamentSettingsServiceProvider`.
- Run the migration after install: `php artisan migrate` (creates `settings` table with `name` (unique) and `value` (json)).
- Optional publishes: `filament-settings-migrations`, `filament-settings-lang`, `filament-settings-views`, `filament-settings-config` (config file currently has no keys).

### Scaffolding a settings page

@verbatim
<code-snippet name="Generate settings page" lang="bash">
php artisan make:filament-settings-page GeneralSettings
</code-snippet>
@endverbatim

Generates two files:

- `app/Filament/Pages/GeneralSettingsPage.php` — extends `VanOns\FilamentSettings\Filament\Pages\SettingsPage`. Define form via `getFormSchema()`.
- `app/Settings/GeneralSettings.php` — extends `VanOns\FilamentSettings\Classes\Settings`. Define `defaults()` and (optionally) override `$settingsName`.

Caveat: the generated `$settingsName` is the **first kebab segment** of the class name only (`Str::before(Str::kebab($name), '-')`). `EmailNotificationSettings` → `email`, not `email-notification`. Override `public string $settingsName` on the data class if you want the full name.

### Public API

| Class / Trait | Purpose |
|---|---|
| `VanOns\FilamentSettings\Filament\Pages\SettingsPage` | Abstract Filament page. Override `getFormSchema(): array` and `protected string $settingsClass`. |
| `VanOns\FilamentSettings\Classes\Settings` | Abstract data class. Override `defaults(): array` and `public string $settingsName`. |
| `VanOns\FilamentSettings\Filament\Traits\CanMutateData` | Mounted on `SettingsPage` via `HasSettings`. Override `mutateFormDataBeforeFill()` / `mutateFormDataBeforeSave()` on the page. |
| `VanOns\FilamentSettings\Filament\Actions\SaveAction` | Preconfigured save action (label, `mod+s` keybinding, calls `submit`). Returned by `getActions()`. |
| `VanOns\FilamentSettings\Models\Settings` | Eloquent model. Static `Settings::set(string $key, mixed $value)` and `Settings::getValue(string $key): mixed` for direct access. |

### Conventions

- A "settings group" = one row in `settings`, keyed by `$settingsName`. Pair one `SettingsPage` subclass with one `Settings` subclass.
- Read settings outside Filament with `(new GeneralSettings())->get('site.title')` or `Settings::getValue('general')`. `get()` accepts dot notation (uses `Arr::get`).
- `defaults()` is only used when **no row exists yet** for the group. Once saved, defaults are not merged in. Plan for missing keys when adding new fields later.
- The page form is preconfigured with `->statePath('settings')->columns(3)`. Override `form()` on the page to change column count or state path.
- Cast complex types in `defaults()` and inside `mutateFormDataBeforeFill/Save` — the model casts the whole `value` blob as `json`, no per-key casting.

### Example settings page

@verbatim
<code-snippet name="Settings page" lang="php">
namespace App\Filament\Pages;

use App\Settings\GeneralSettings;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use VanOns\FilamentSettings\Filament\Pages\SettingsPage;

class GeneralSettingsPage extends SettingsPage
{
    protected static ?string $title = 'General';
    protected string $settingsClass = GeneralSettings::class;

    public function getFormSchema(): array
    {
        return [
            Section::make('Site')
                ->columnSpanFull()
                ->schema([
                    TextInput::make('site.title')->required(),
                    TextInput::make('site.tagline'),
                ]),
        ];
    }

    public function mutateFormDataBeforeSave(array $data): array
    {
        return $data;
    }
}
</code-snippet>
@endverbatim

@verbatim
<code-snippet name="Settings data class" lang="php">
namespace App\Settings;

use VanOns\FilamentSettings\Classes\Settings;

class GeneralSettings extends Settings
{
    public string $settingsName = 'general';

    public function defaults(): array
    {
        return [
            'site' => [
                'title' => config('app.name'),
                'tagline' => null,
            ],
        ];
    }
}
</code-snippet>
@endverbatim
