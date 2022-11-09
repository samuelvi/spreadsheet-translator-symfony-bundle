<?php

/*
 * This file is part of the Atico/SpreadsheetTranslator package.
 *
 * (c) Samuel Vicent <samuelvicent@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Atico\Bundle\SpreadsheetTranslatorBundle\DependencyInjection;

use Exception;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Processor;

/**
 * Set Default configuration for the main Manager Service
 * Define Custom Alias (atico_st2)
 *
 * @author Samuel Vicent <samuelvicent@gmail.com>
 */
class SpreadsheetTranslatorExtension extends Extension
{
    /** @throws Exception */
    public function load(array $configs, ContainerBuilder $container) : void
    {
        $this->loadServices($container);
        $this->injectConfigurationIntoSpreadsheetTranslatorManager($configs, $container);
    }

    /** @throws Exception */
    private function loadServices(ContainerBuilder $container) : void
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );
        $loader->load('services.yml');
    }

    private function injectConfigurationIntoSpreadsheetTranslatorManager(array $configs, ContainerBuilder $container) : void
    {
        $processor = new Processor();
        $configuration = new Configuration();
        
        $config = $processor->processConfiguration($configuration, $configs);
        $container->getDefinition('atico.spreadsheet_translator.manager')->setArgument(0, $config);
    }

    public function getAlias(): string
    {
        return 'atico_spreadsheet_translator';
    }
}