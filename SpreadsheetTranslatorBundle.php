<?php

/*
 * This file is part of the Atico/SpreadsheetTranslator package.
 *
 * (c) Samuel Vicent <samuelvicent@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AticO\Bundle\SpreadsheetTranslatorBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SpreadsheetTranslatorBundle extends Bundle
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
    }

    /**
     * Returns the bundle's container extension class.
     *
     * @return string
     */
    protected function getContainerExtensionClass()
    {
        $basename = preg_replace('/Bundle$/', '', $this->getName());
        return $this->getNamespace() . '\\DependencyInjection\\' . $basename . 'Extension';
    }

    /**
     * Creates the bundle's container extension.
     *
     * @return ExtensionInterface|null
     */
    protected function createContainerExtension()
    {
        if (class_exists($class = $this->getContainerExtensionClass())) {
            return new $class();
        }
    }

    public function getContainerExtension()
    {
        return $this->createContainerExtension();
    }
}
