<?php

/*
<<<<<<< HEAD
 * This file is part of the Atico/Spreadsheet2Translation package.
=======
 * This file is part of the Atico/SpreadsheetTranslator package.
>>>>>>> latest_branch
 *
 * (c) Samuel Vicent <samuelvicent@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Atico\Bundle\SpreadsheetTranslatorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration File
 *
 * @author Samuel Vicent <samuelvicent@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('atico_spreadsheet_translator');

        $rootNode
            ->children()
                ->arrayNode('frontend')
                    ->children()
                        ->arrayNode('provider')
                            ->children()
                                ->scalarNode('name')->isRequired()
                                ->end()
                                ->scalarNode('source_resource')->isRequired()
                                ->end()
                                ->scalarNode('credentials_path')
                                ->end()
                                ->scalarNode('client_secret_path')
                                ->end()
                                ->scalarNode('document_id')
                                ->end()
                                ->scalarNode('application_name')
                                ->end()
                                ->variableNode('scopes')
                                ->end()
                            ->end()

                        ->end()
                        ->arrayNode('exporter')
                            ->children()
                                ->scalarNode('format')->defaultValue('xliff')
                                ->end()
                                ->scalarNode('prefix')
                                ->end()
                                ->scalarNode('domain')
                                ->end()
                                ->scalarNode('destination_folder')
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('shared')
                            ->children()
                                ->scalarNode('temp_local_source_file')
                                ->end()
                                ->scalarNode('default_locale')->defaultValue('en')
                                ->end()
                                ->scalarNode('name_separator')->defaultValue('.')
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('parser')
                            ->children()
                                ->scalarNode('format')
                                    ->defaultValue('matrix')
                                ->end()
                            ->end()
                            ->addDefaultsIfNotSet()
                        ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
