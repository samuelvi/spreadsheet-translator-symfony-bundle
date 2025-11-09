<?php

declare(strict_types=1);

/*
 * This file is part of the Atico/SpreadsheetTranslator package.
 *
 * (c) Samuel Vicent <samuelvicent@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Atico\Bundle\SpreadsheetTranslatorBundle\Tests\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Atico\Bundle\SpreadsheetTranslatorBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Processor;

/**
 * @covers \Atico\Bundle\SpreadsheetTranslatorBundle\DependencyInjection\Configuration
 */
final class ConfigurationTest extends TestCase
{
    private Configuration $configuration;

    private Processor $processor;

    protected function setUp(): void
    {
        $this->configuration = new Configuration();
        $this->processor = new Processor();
    }

    public function testGetConfigTreeBuilder(): void
    {
        $treeBuilder = $this->configuration->getConfigTreeBuilder();

        $this->assertInstanceOf(TreeBuilder::class, $treeBuilder);
    }

    public function testMinimalConfiguration(): void
    {
        $config = [
            'atico_spreadsheet_translator' => [
                'frontend' => [
                    'provider' => [
                        'name' => 'local_file',
                        'source_resource' => '/path/to/file.xlsx',
                    ],
                ],
            ],
        ];

        $processedConfig = $this->processor->processConfiguration($this->configuration, $config);

        $this->assertArrayHasKey('frontend', $processedConfig);
        $this->assertSame('local_file', $processedConfig['frontend']['provider']['name']);
        $this->assertSame('/path/to/file.xlsx', $processedConfig['frontend']['provider']['source_resource']);
    }

    public function testFullConfiguration(): void
    {
        $config = [
            'atico_spreadsheet_translator' => [
                'frontend' => [
                    'provider' => [
                        'name' => 'google_drive',
                        'source_resource' => 'https://docs.google.com/spreadsheets/d/xxx',
                        'credentials_path' => '/path/to/credentials.json',
                        'client_secret_path' => '/path/to/client_secret.json',
                        'document_id' => 'xxx',
                        'application_name' => 'MyApp',
                    ],
                    'exporter' => [
                        'format' => 'yml',
                        'prefix' => 'app_',
                        'destination_folder' => '/path/to/translations',
                    ],
                    'shared' => [
                        'temp_local_source_file' => '/tmp/spreadsheet.xlsx',
                        'default_locale' => 'es',
                        'name_separator' => '_',
                        'lazy_mode' => true,
                    ],
                    'parser' => [
                        'format' => 'xlsx',
                    ],
                ],
            ],
        ];

        $processedConfig = $this->processor->processConfiguration($this->configuration, $config);

        // Provider assertions
        $this->assertSame('google_drive', $processedConfig['frontend']['provider']['name']);
        $this->assertSame('https://docs.google.com/spreadsheets/d/xxx', $processedConfig['frontend']['provider']['source_resource']);
        $this->assertSame('/path/to/credentials.json', $processedConfig['frontend']['provider']['credentials_path']);
        $this->assertSame('/path/to/client_secret.json', $processedConfig['frontend']['provider']['client_secret_path']);
        $this->assertSame('xxx', $processedConfig['frontend']['provider']['document_id']);
        $this->assertSame('MyApp', $processedConfig['frontend']['provider']['application_name']);

        // Exporter assertions
        $this->assertSame('yml', $processedConfig['frontend']['exporter']['format']);
        $this->assertSame('app_', $processedConfig['frontend']['exporter']['prefix']);
        $this->assertSame('/path/to/translations', $processedConfig['frontend']['exporter']['destination_folder']);

        // Shared assertions
        $this->assertSame('/tmp/spreadsheet.xlsx', $processedConfig['frontend']['shared']['temp_local_source_file']);
        $this->assertSame('es', $processedConfig['frontend']['shared']['default_locale']);
        $this->assertSame('_', $processedConfig['frontend']['shared']['name_separator']);
        $this->assertTrue($processedConfig['frontend']['shared']['lazy_mode']);

        // Parser assertions
        $this->assertSame('xlsx', $processedConfig['frontend']['parser']['format']);
    }

    public function testDefaultValues(): void
    {
        $config = [
            'atico_spreadsheet_translator' => [
                'frontend' => [
                    'provider' => [
                        'name' => 'local_file',
                        'source_resource' => '/path/to/file.xlsx',
                    ],
                    'exporter' => [],
                    'shared' => [],
                    'parser' => [],
                ],
            ],
        ];

        $processedConfig = $this->processor->processConfiguration($this->configuration, $config);

        // Check default values
        $this->assertSame('xliff', $processedConfig['frontend']['exporter']['format']);
        $this->assertSame('', $processedConfig['frontend']['exporter']['prefix']);
        $this->assertSame('en', $processedConfig['frontend']['shared']['default_locale']);
        $this->assertSame('.', $processedConfig['frontend']['shared']['name_separator']);
        $this->assertFalse($processedConfig['frontend']['shared']['lazy_mode']);
        $this->assertSame('matrix', $processedConfig['frontend']['parser']['format']);
    }

    public function testMissingProviderName(): void
    {
        $this->expectException(InvalidConfigurationException::class);

        $config = [
            'atico_spreadsheet_translator' => [
                'frontend' => [
                    'provider' => [
                        'source_resource' => '/path/to/file.xlsx',
                    ],
                ],
            ],
        ];

        $this->processor->processConfiguration($this->configuration, $config);
    }

    public function testMissingSourceResource(): void
    {
        $this->expectException(InvalidConfigurationException::class);

        $config = [
            'atico_spreadsheet_translator' => [
                'frontend' => [
                    'provider' => [
                        'name' => 'local_file',
                    ],
                ],
            ],
        ];

        $this->processor->processConfiguration($this->configuration, $config);
    }

    public function testMultipleNamedConfigurations(): void
    {
        $config = [
            'atico_spreadsheet_translator' => [
                'frontend' => [
                    'provider' => [
                        'name' => 'local_file',
                        'source_resource' => '/path/to/frontend.xlsx',
                    ],
                ],
                'backend' => [
                    'provider' => [
                        'name' => 'google_drive',
                        'source_resource' => 'https://docs.google.com/spreadsheets/d/backend',
                    ],
                ],
            ],
        ];

        $processedConfig = $this->processor->processConfiguration($this->configuration, $config);

        $this->assertArrayHasKey('frontend', $processedConfig);
        $this->assertArrayHasKey('backend', $processedConfig);
        $this->assertSame('local_file', $processedConfig['frontend']['provider']['name']);
        $this->assertSame('google_drive', $processedConfig['backend']['provider']['name']);
    }
}
