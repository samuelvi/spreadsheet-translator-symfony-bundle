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

use Atico\SpreadsheetTranslator\Core\SpreadsheetTranslator;
use Atico\Bundle\SpreadsheetTranslatorBundle\DependencyInjection\SpreadsheetTranslatorExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @covers \Atico\Bundle\SpreadsheetTranslatorBundle\DependencyInjection\SpreadsheetTranslatorExtension
 */
final class SpreadsheetTranslatorExtensionTest extends TestCase
{
    private SpreadsheetTranslatorExtension $spreadsheetTranslatorExtension;

    private ContainerBuilder $containerBuilder;

    protected function setUp(): void
    {
        $this->spreadsheetTranslatorExtension = new SpreadsheetTranslatorExtension();
        $this->containerBuilder = new ContainerBuilder();
    }

    public function testGetAlias(): void
    {
        $this->assertSame('atico_spreadsheet_translator', $this->spreadsheetTranslatorExtension->getAlias());
    }

    public function testLoadServices(): void
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

        $this->spreadsheetTranslatorExtension->load($config, $this->containerBuilder);

        // Verify the service is registered
        $this->assertTrue($this->containerBuilder->hasDefinition('atico.spreadsheet_translator.manager'));
    }

    public function testLoadInjectsConfiguration(): void
    {
        $config = [
            'atico_spreadsheet_translator' => [
                'frontend' => [
                    'provider' => [
                        'name' => 'local_file',
                        'source_resource' => '/path/to/file.xlsx',
                    ],
                    'exporter' => [
                        'format' => 'yml',
                        'prefix' => 'test_',
                    ],
                    'shared' => [
                        'default_locale' => 'es',
                        'lazy_mode' => true,
                    ],
                ],
            ],
        ];

        $this->spreadsheetTranslatorExtension->load($config, $this->containerBuilder);

        $definition = $this->containerBuilder->getDefinition('atico.spreadsheet_translator.manager');
        $arguments = $definition->getArguments();

        $this->assertCount(1, $arguments);
        $this->assertIsArray($arguments[0]);
        $this->assertArrayHasKey('frontend', $arguments[0]);

        // Verify configuration was processed correctly
        $frontendConfig = $arguments[0]['frontend'];
        $this->assertSame('local_file', $frontendConfig['provider']['name']);
        $this->assertSame('/path/to/file.xlsx', $frontendConfig['provider']['source_resource']);
        $this->assertSame('yml', $frontendConfig['exporter']['format']);
        $this->assertSame('test_', $frontendConfig['exporter']['prefix']);
        $this->assertSame('es', $frontendConfig['shared']['default_locale']);
        $this->assertTrue($frontendConfig['shared']['lazy_mode']);
    }

    public function testLoadWithDefaultValues(): void
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

        $this->spreadsheetTranslatorExtension->load($config, $this->containerBuilder);

        $definition = $this->containerBuilder->getDefinition('atico.spreadsheet_translator.manager');
        $arguments = $definition->getArguments();
        $frontendConfig = $arguments[0]['frontend'];

        // Verify default values
        $this->assertSame('xliff', $frontendConfig['exporter']['format']);
        $this->assertSame('', $frontendConfig['exporter']['prefix']);
        $this->assertSame('en', $frontendConfig['shared']['default_locale']);
        $this->assertSame('.', $frontendConfig['shared']['name_separator']);
        $this->assertFalse($frontendConfig['shared']['lazy_mode']);
        $this->assertSame('matrix', $frontendConfig['parser']['format']);
    }

    public function testLoadWithMultipleConfigurations(): void
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
                    'exporter' => [
                        'format' => 'php',
                    ],
                ],
            ],
        ];

        $this->spreadsheetTranslatorExtension->load($config, $this->containerBuilder);

        $definition = $this->containerBuilder->getDefinition('atico.spreadsheet_translator.manager');
        $arguments = $definition->getArguments();

        $this->assertArrayHasKey('frontend', $arguments[0]);
        $this->assertArrayHasKey('backend', $arguments[0]);

        $this->assertSame('local_file', $arguments[0]['frontend']['provider']['name']);
        $this->assertSame('google_drive', $arguments[0]['backend']['provider']['name']);
        $this->assertSame('php', $arguments[0]['backend']['exporter']['format']);
    }

    public function testManagerServiceClass(): void
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

        $this->spreadsheetTranslatorExtension->load($config, $this->containerBuilder);

        $definition = $this->containerBuilder->getDefinition('atico.spreadsheet_translator.manager');
        $this->assertSame(SpreadsheetTranslator::class, $definition->getClass());
    }
}
