
<br/>

Spreadsheet Translator Symfony Bundle
=====================================

The Spreadsheet Translator Symfony Bundle allows creating **translation files** for your web projects from **spreadsheet files (local or remote excel files).**

There are some demos at the end of the documentation that aims to help you as much as possible.

This bundle is able to manage Spreadsheet files (xls/xlsx) from a local drive or cloud services such as: Google Drive and Microsoft One Drive, both of them with or without authentication.

The main advantages for managing web translations from spreadsheet files are:

- Avoid programming a backend.
- Avoid implementing/setting up a security system for users with the role translator.
- For cloud documents, the translation file can be editable by several users at the same time.
- Avoid installing aditional desktop software for managing not really human readable translation file formats (xliff, yml, php).
- Several translation files can be easily automated and distributed to several servers/environments.
- Using third party SpreadSheet apps, such as Google Drive, allow watching several translations in several languages at a glance, because of the column view. 
- Most of these third party apps bring great tools out-of-the-box such as: search, copy, paste, replace text. (Microsoft Excel, Free Office, Google Drive, One Drive).
- Almost everyone is familiar with existing spreadsheets apps, most of the times more productive than onward/backward web based backend systems.
- Avoid innecessary database queries for each translation entry or complex cache infrastructure.


<br/>

Example of Spreadsheet File with a single tab
------------

- <a href="https://docs.google.com/spreadsheets/d/1MgpsX-UXVVx1UAmbQvKrrE8yvWBt62haKuBieyOL_cE/edit?usp=sharing">Example of spreadsheet file in google drive without auth.</a>


<br/>

Adapters
--------

The bundle is constructed having in mind separation of concerns, so functionallities are splitted in 3 types of adapters. 

- **Providers**: Allow to grasp the Spreadsheet information from 5 different sources:
  - **Local file** (Default)
  - Google Drive shared view document
  - Google Drive with authentication
  - Microsft One Drive shared view document
  - Microsft One Drive with authentication

- **Readers**: Allow to read the Spreadsheet file in different manners to expose the data to an exporter. 
  - **Matrix** (Default)
  - Xlsx

- **Exporters**: Allow to export the provided Spreadsheet file into 3 types of translation file formats:
  - **Xliff** (Default)
  - Yaml
  - Php


<br/>

Installation
------------

Add the samuelvi/spreadsheet-translator-symfony-bundle package to your require section in the composer.json file (*)

```bash
$ composer require samuelvi/spreadsheet-translator-symfony-bundle
```

**For Symfony 7+**, the bundle will be automatically registered in `config/bundles.php`. If you need to register it manually:

```php
<?php
// config/bundles.php
return [
    // ...
    Atico\Bundle\SpreadsheetTranslatorBundle\SpreadsheetTranslatorBundle::class => ['all' => true],
    // ...
];
```

**For older Symfony versions** (before Symfony 4), add it to your application's kernel:

```php
<?php
// app/AppKernel.php
public function registerBundles()
{
    $bundles = array(
        // ...
        new Atico\Bundle\SpreadsheetTranslatorBundle\SpreadsheetTranslatorBundle(),
        // ...
    );
    ...
 }
 ```

You need to separately install 3 adapters: a provider, a reader and an exporter.

(*) For the default configuration to work, three additional packages are required:

```bash
$ composer require samuelvi/spreadsheet-translator-provider-localfile
$ composer require samuelvi/spreadsheet-translator-reader-matrix
$ composer require samuelvi/spreadsheet-translator-exporter-xliff
```


<br/>

Configuration
-------------

**For Symfony 7+**, add to `config/packages/atico_spreadsheet_translator.yaml`:

```yaml
# config/packages/atico_spreadsheet_translator.yaml
atico_spreadsheet_translator:
    frontend:
        provider:
            name: 'local_file' # 'google_drive', 'one_drive', 'google_drive_auth', 'one_drive_auth'
            source_resource: '%kernel.project_dir%/var/your_spreadsheet_file.xls'
        exporter:
            format: 'xliff' # 'php', 'yml'
            prefix: 'demo_'
            domain: 'common'
            destination_folder: '%kernel.project_dir%/translations'
        shared:
            default_locale: 'en'
            name_separator: '.' # translation subkey separator, i.e, homepage.h1, homepage.h2...
            lazy_mode: true # constructs translation keys based on previous key values, avoid repeating same subkey several times
```

**For older Symfony versions**, add to `app/config/config.yml`:

```yaml
# app/config/config.yml
atico_spreadsheet_translator:
    frontend:
        provider:
            name: 'local_file'
            source_resource: '%kernel.root_dir%/../var/your_spreadsheet_file.xls'
        exporter:
            format: 'xliff'
            prefix: 'demo_'
            domain: 'common'
            destination_folder: '%kernel.project_dir%/app/Resources/translations'
        shared:
            default_locale: 'en'
            name_separator: '.'
            lazy_mode: true
```


<br/>

Adapters as independent Packages
--------------------------------

- Providers:

```bash
# Local File Provider
$ composer require samuelvi/spreadsheet-translator-provider-localfile

# Google Drive Provider
$ composer require samuelvi/spreadsheet-translator-provider-googledrive

# Google Drive Provider with Authentication
$ composer require samuelvi/spreadsheet-translator-provider-googledriveauth

# One Drive Provider
$ composer require samuelvi/spreadsheet-translator-provider-onedrive

# One Drive Provider with Authentication
$ composer require samuelvi/spreadsheet-translator-provider-onedriveauth
```

- Readers:

```bash
# Matrix reader
$ composer require samuelvi/spreadsheet-translator-reader-matrix

# Xlsx reader
$ composer require samuelvi/spreadsheet-translator-reader-xlsx
```

- Exporters:

```bash
# Xliff exporter
$ composer require samuelvi/spreadsheet-translator-exporter-xliff

# Yml exporter
$ composer require samuelvi/spreadsheet-translator-exporter-yml

# Php exporter
$ composer require samuelvi/spreadsheet-translator-exporter-php
```

Links to the libraries:

- <a href="https://github.com/samuelvi/spreadsheet-translator-exporter-php">Php Exporter</a>
- <a href="https://github.com/samuelvi/spreadsheet-translator-exporter-xliff">Xliff Exporter</a>
- <a href="https://github.com/samuelvi/spreadsheet-translator-exporter-yml">Yaml Exporter</a>


Structure of Spreadsheet File with a single tab
------------

```
| section  | subsection | es_ES                                                 | en_GB                                     | fr_FR                                                  |
|----------|------------|-------------------------------------------------------|-------------------------------------------|--------------------------------------------------------|
| homepage | title      | Traductor Hoja de Cálculo                             | Spreadsheet translator                    | Feuille de calcul du traducteur                        |
| hompage  | subtitle   | Traductor de páginas web a partir de hojas de cálculo | Translator of web pages from spreadsheets | Traducteur de pages Web à partir de feuilles de calcul |
|          |            |                                                       |                                           |                                                        |
```

tab name: common




Section and subsection will be joined with a dot, you can specify another character by setting a custom shared->name_separator value.
In this case there will be 2 different translation keys: homepage.title and homepage.subtitle.


The package automatically detects locales by their format, in this example these are the titled columns: es_ES, en_GB and fr_FR.


The package will translate the tab with the name specified in the configuration exporter->domain value. It's planned to extend this behaviour to several tabs or all the tabs.


The configuration allows a lazy mode parameter (shared => lazy_mode). This will allow inheriting which helps writing faster, having a more clear view of the translation keys and avoiding copy and paste mistakes.

Structure of Spreadsheet File with a single tab in lazy mode
------------

```
| section  | group    | container  | en_GB                                     
|----------|----------|--------------------------------------------
| contact  | title    | h1         | Contact form  
|          | subtitle | h2         | Don't be in doubt
|          | form     | first_name | First name                    
|          |          | email      | E-mail  
  
```






There will be 3 files created at app/Resources/translations: demo_common.es_ES.xliff, demo_common.en_GB.xliff and demo_common.fr_FR.xliff. Please notice the prefix demo_, this is the value set at the configuration var exporter->prefix.


The contents for the exporter->format to **xliff**, the resulting file contents for **es_ES (demo_common.es_ES.xliff)** are:

```xml
<?xml version="1.0"?>
<xliff version="1.2" xmlns="urn:oasis:names:tc:xliff:document:1.2">
    <file source-language="en" target-language="en" datatype="plaintext" original="file.ext">
        <body>
            <trans-unit id="homepage_title">
                <source>homepage_title</source>
                <target><![CDATA[Traductor Hoja de Cálculo]]></target>
            </trans-unit>
            <trans-unit id="homepage_subtitle">
                <source>homepage_subtitle</source>
                <target><![CDATA[Traductor de páginas web a partir de hojas de cálculo]]></target>
            </trans-unit>
        </body>
    </file>
</xliff> 
```

There are two additional exporter formats adapters, you must include them in your composer json before using them.

Setting exporter->format to **php** would result for **en_GB (demo_common.en_GB.php)** the following translation file:

```php
<?php
return array (
  'homepage_title' => 'Spreadsheet translator',
  'homepage_subtitle' => 'Translator of web pages from spreadsheets',
);
```

And finally, setting the exporter->format to **yml** would result for **fr_FR (demo_common.fr_FR.yml)** the following translation file:

```yml
homepage: 
    title: >
        Feuille de calcul du traducteur
homepage: 
    subtitle: >
        Traducteur de pages Web à partir de feuilles de calcul
```


<br/>

 Lightweight Symfony Demos
-----
<ol>

<li>
<a href="https://github.com/samuelvi/spreadsheet-translator-symfony-demo-local-file-provider-php-exporter" target="_blank">Local file as a spreadsheet source file and php format for translated file.</a>
</li>

<li>
<a href="https://github.com/samuelvi/translator-symfony-demo-google-drive-provider-yml-exporter" target="_blank">Google Spreadsheet with read permisions as source (without Auth) and yml format for translated file.</a>
<li>

<a href="https://github.com/samuelvi/translator-symfony-demo-google-auth-to-php" target="_blank">Google Spreadsheet with Authentication required as source and php format for translated file.</a>
</li>

</ol>

<br/>

Requirements
------------

  * PHP >=8.4
  * Symfony ^7.0

### Modern PHP Features

This bundle leverages modern PHP 8.4+ features including:

- **PHP 8.3+ Override Attribute**: Uses `#[Override]` attribute for better code safety when overriding parent methods
- **Strict Types**: All files use `declare(strict_types=1)` for type safety
- **Type Declarations**: Full return type and parameter type declarations throughout the codebase
- **Constructor Property Promotion**: Modern constructor syntax where applicable


<br/>

Development
-----------

### Code Quality with Rector

This bundle uses [Rector](https://getrector.com/) for automated code quality and upgrades to PHP 8.4+ and Symfony 7.

```bash
# Check what changes Rector would make (dry-run)
vendor/bin/rector process --dry-run

# Apply changes
vendor/bin/rector process
```


<br/>

Contributing
------------

We welcome contributions to this project, including pull requests and issues (and discussions on existing issues).

If you'd like to contribute code but aren't sure what, the issues list is a good place to start. If you're a first-time code contributor, you may find Github's guide to <a href="https://guides.github.com/activities/forking/">forking projects</a> helpful.

All contributors (whether contributing code, involved in issue discussions, or involved in any other way) must abide by our code of conduct.


<br/>

License
-------

Spreadsheet Translator Symfony Bundle is licensed under the MIT License. See the LICENSE file for full details.