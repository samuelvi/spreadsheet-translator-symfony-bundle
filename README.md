
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

Add the atico/spreadsheet-translator-symfony-bundle package to your require section in the composer.json file (*)

```bash
$ composer require atico/spreadsheet-translator-symfony-bundle master-dev
```

Add the Spreadsheet Translator Symfony Bundle to your application's kernel:

```php
<?php
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
$ composer require atico/spreadsheet-translator-provider-localfile master-dev
$ composer require atico/spreadsheet-translator-reader-matrix master-dev
$ composer require atico/spreadsheet-translator-exporter-xliff master-dev
```


<br/>

Configuration
-------------

Add to config.yml the following entry:

```yaml
atico_spreadsheet_translator:
    frontend:
        provider:
            name: 'local_file' # 'google_drive', 'one_drive', 'google_drive_auth', 'one_drive_auth'
            source_resource: '%kernel.root_dir%/../var/your_spreadsheet_file.xls'
        exporter:
            format: 'xliff' # 'php', 'yml'
            prefix: 'demo_'
            domain: 'common'
            destination_folder: '%kernel.project_dir%/app/Resources/translations'
        shared:
            default_locale: 'en'
            name_separator: '.' # translation subkey separator, i.e, homepage.h1, homepage.h2...

```


<br/>

Adapters as independent Packages
--------------------------------

- Providers:

```bash
# Local File Provider 
$ composer require atico/spreadsheet-translator-provider-localfile master-dev 
 
# Google Drive Provider 
$ composer require atico/spreadsheet-translator-provider-googledrive master-dev 
 
# Google Drive Provider with Authentication
$ composer require atico/spreadsheet-translator-provider-googledriveauth master-dev  
 
# One Drive Provider 
$ composer require atico/spreadsheet-translator-provider-onedrive master-dev  
 
# One Drive Provider with Authentication
$ composer require atico/spreadsheet-translator-provider-onedriveauth master-dev  
```

- Readers:

```bash
# Matrix reader
$ composer require atico/spreadsheet-translator-reader-matrix master-dev 
 
# Xlsx reader
$ composer require atico/spreadsheet-translator-reader-xlsx master-dev 
```

- Exporters:

```bash
# Xliff exporter
$ composer require atico/spreadsheet-translator-exporter-xliff master-dev 
 
# Yml exporter
$ composer require atico/spreadsheet-translator-exporter-yml master-dev 
 
# Php exporter
$ composer require atico/spreadsheet-translator-exporter-php master-dev 
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

Demos
-----

<a href="https://github.com/samuelvi/spreadsheet-translator-symfony-demo-local-file-provider-php-exporter" target="_blank">1) Lightweight Symfony Demo Application for the Spreadsheet Translator functionallity considering a local file as a spreadsheet source file and php format for translated file.</a>


<br/>

Requirements
------------

  * PHP >=5.5.9
  * Symfony ~2.3|~3.0


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