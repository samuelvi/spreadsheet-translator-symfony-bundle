<?php

/*
 * This file is part of the Atico/SpreadsheetTranslator package.
 *
 * (c) Samuel Vicent <samuelvicent@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Atico\Bundle\SpreadsheetTranslatorBundle;

use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SpreadsheetTranslatorBundle extends Bundle
{
    /**
     * Returns the bundle's container extension class.
     */
    protected function getContainerExtensionClass(): string
    {
        $basename = preg_replace('/Bundle$/', '', $this->getName());
        return $this->getNamespace() . '\\DependencyInjection\\' . $basename . 'Extension';
    }

    /**
     * Creates the bundle's container extension.
     */
    protected function createContainerExtension(): ?ExtensionInterface
    {
        if (class_exists($class = $this->getContainerExtensionClass())) {
            return new $class();
        }

        return parent::createContainerExtension();
    }

    public function getContainerExtension(): ?ExtensionInterface
    {
        return $this->createContainerExtension();
    }
}
