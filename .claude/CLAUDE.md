# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Symfony bundle that generates translation files (xliff, yml, php) from spreadsheet files (xls/xlsx). It supports reading spreadsheets from local files, Google Drive (with/without auth), and OneDrive (with/without auth).

**Repository**: `samuelvi/spreadsheet-translator-symfony-bundle`
**Namespace**: `Atico\Bundle\SpreadsheetTranslatorBundle`

## Requirements

- PHP >=8.4
- Symfony ^7.0
- All files use strict types (`declare(strict_types=1)`)
- Modern PHP 8.4+ features including `#[Override]` attribute

## Development Commands

### Code Quality and Upgrades with Rector

```bash
# Check what changes Rector would make (dry-run)
vendor/bin/rector process --dry-run

# Apply Rector changes
vendor/bin/rector process
```

### Composer

```bash
# Install dependencies
composer install

# Update dependencies
composer update

# Validate composer.json
composer validate

# Check platform requirements
composer check-platform-reqs
```

### PHP Syntax Validation

```bash
# Check PHP syntax of a file
php -l <file.php>
```

## Architecture

### Bundle Structure

The bundle follows Symfony best practices with a simple, focused architecture:

```
SpreadsheetTranslatorBundle.php       # Main bundle class
├── DependencyInjection/
│   ├── Configuration.php             # Bundle configuration tree
│   └── SpreadsheetTranslatorExtension.php  # DI extension
└── Resources/
    └── config/
        └── services.yml              # Service definitions
```

### Core Service

The bundle registers a single service (`atico.spreadsheet_translator.manager`) that is an instance of `Atico\SpreadsheetTranslator\Core\SpreadsheetTranslator` from the `samuelvi/spreadsheet-translator-core` package. All actual translation logic is delegated to this core library.

### Configuration System

Configuration is defined using Symfony's TreeBuilder API in `DependencyInjection/Configuration.php`. The configuration structure supports:

- **frontend**: Named configuration profiles (allows multiple configurations)
  - **provider**: Spreadsheet source configuration
    - `name`: Provider type (local_file, google_drive, one_drive, etc.)
    - `source_resource`: Path/URL to spreadsheet
    - Optional auth parameters (credentials_path, client_secret_path, etc.)
  - **exporter**: Output format configuration
    - `format`: Output format (xliff, yml, php)
    - `prefix`: Prefix for generated files
    - `destination_folder`: Where to save translation files
  - **shared**: Common settings
    - `default_locale`: Default locale (e.g., 'en')
    - `name_separator`: Character to join translation key parts (default: '.')
    - `lazy_mode`: Enable key inheritance (default: false)
  - **parser**: Spreadsheet parsing configuration
    - `format`: Parser format (matrix, xlsx)

### Extension Points (Adapters)

The bundle uses a plugin architecture with three adapter types (installed as separate packages):

1. **Providers**: Control where spreadsheet data comes from
   - `samuelvi/spreadsheet-translator-provider-localfile` (default)
   - `samuelvi/spreadsheet-translator-provider-googledrive`
   - `samuelvi/spreadsheet-translator-provider-onedrive`
   - Auth variants for Google Drive and OneDrive

2. **Readers**: Control how spreadsheet data is parsed
   - `samuelvi/spreadsheet-translator-reader-matrix` (default)
   - `samuelvi/spreadsheet-translator-reader-xlsx`

3. **Exporters**: Control output format
   - `samuelvi/spreadsheet-translator-exporter-xliff` (default)
   - `samuelvi/spreadsheet-translator-exporter-yml`
   - `samuelvi/spreadsheet-translator-exporter-php`

### Bundle Registration

**Symfony 7+**: Auto-registered via Symfony Flex in `config/bundles.php`

**Manual registration** (if needed):
```php
// config/bundles.php
return [
    Atico\Bundle\SpreadsheetTranslatorBundle\SpreadsheetTranslatorBundle::class => ['all' => true],
];
```

### DI Extension Details

`SpreadsheetTranslatorExtension`:
- Loads services from `Resources/config/services.yml`
- Processes configuration via `Configuration` class
- Injects processed config into the manager service as first argument
- Uses custom alias: `atico_spreadsheet_translator`

## Rector Configuration

The bundle uses Rector for code quality and PHP/Symfony upgrades. Configuration in `rector.php`:

**Paths analyzed**:
- `DependencyInjection/`
- `SpreadsheetTranslatorBundle.php`
- `Resources/`

**Rule sets applied**:
- PHP 8.4+ upgrades (`LevelSetList::UP_TO_PHP_84`)
- Code quality improvements
- Dead code removal
- Type declarations
- Early returns
- Privatization
- Symfony 7 best practices
- Symfony constructor injection
- Doctrine code quality

**Additional features**:
- Auto-import class names
- Remove unused imports

## Configuration Example

Minimal configuration (Symfony 7+):

```yaml
# config/packages/atico_spreadsheet_translator.yaml
atico_spreadsheet_translator:
    frontend:
        provider:
            name: 'local_file'
            source_resource: '%kernel.project_dir%/var/translations.xlsx'
        exporter:
            format: 'xliff'
            prefix: 'app_'
            domain: 'messages'
            destination_folder: '%kernel.project_dir%/translations'
        shared:
            default_locale: 'en'
            name_separator: '.'
            lazy_mode: true
```

## How Translation Works

1. **Spreadsheet Structure**: Each spreadsheet has locale columns (e.g., `en_GB`, `es_ES`) and key columns (section, subsection, etc.)
2. **Key Building**: Key columns are joined with the `name_separator` (e.g., `homepage.title`)
3. **Lazy Mode**: When enabled, empty key columns inherit from the previous row's value, reducing repetition
4. **Tab Selection**: Only the tab matching `exporter.domain` is processed
5. **Output**: Generates one file per locale with the pattern: `{prefix}{domain}.{locale}.{format}`
