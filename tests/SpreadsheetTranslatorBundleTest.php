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

namespace Atico\Bundle\SpreadsheetTranslatorBundle\Tests;

use Atico\Bundle\SpreadsheetTranslatorBundle\DependencyInjection\SpreadsheetTranslatorExtension;
use Atico\Bundle\SpreadsheetTranslatorBundle\SpreadsheetTranslatorBundle;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Atico\Bundle\SpreadsheetTranslatorBundle\SpreadsheetTranslatorBundle
 */
final class SpreadsheetTranslatorBundleTest extends TestCase
{
    private SpreadsheetTranslatorBundle $spreadsheetTranslatorBundle;

    protected function setUp(): void
    {
        $this->spreadsheetTranslatorBundle = new SpreadsheetTranslatorBundle();
    }

    public function testGetContainerExtension(): void
    {
        $extension = $this->spreadsheetTranslatorBundle->getContainerExtension();

        $this->assertInstanceOf(SpreadsheetTranslatorExtension::class, $extension);
        $this->assertSame('atico_spreadsheet_translator', $extension->getAlias());
    }

    public function testGetContainerExtensionClass(): void
    {
        $reflectionClass = new \ReflectionClass($this->spreadsheetTranslatorBundle);
        $reflectionMethod = $reflectionClass->getMethod('getContainerExtensionClass');

        $extensionClass = $reflectionMethod->invoke($this->spreadsheetTranslatorBundle);

        $this->assertSame(
            SpreadsheetTranslatorExtension::class,
            $extensionClass
        );
    }

    public function testCreateContainerExtension(): void
    {
        $reflectionClass = new \ReflectionClass($this->spreadsheetTranslatorBundle);
        $reflectionMethod = $reflectionClass->getMethod('createContainerExtension');

        $extension = $reflectionMethod->invoke($this->spreadsheetTranslatorBundle);

        $this->assertInstanceOf(SpreadsheetTranslatorExtension::class, $extension);
    }

    public function testBundleName(): void
    {
        $this->assertSame('SpreadsheetTranslatorBundle', $this->spreadsheetTranslatorBundle->getName());
    }

    public function testBundleNamespace(): void
    {
        $this->assertSame(
            'Atico\Bundle\SpreadsheetTranslatorBundle',
            $this->spreadsheetTranslatorBundle->getNamespace()
        );
    }

    public function testBundlePath(): void
    {
        $expectedPath = dirname(__DIR__);
        $this->assertSame($expectedPath, $this->spreadsheetTranslatorBundle->getPath());
    }
}
