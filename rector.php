<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\Symfony\Set\SymfonySetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/DependencyInjection',
        __DIR__ . '/SpreadsheetTranslatorBundle.php',
        __DIR__ . '/Resources',
    ]);

    // define sets of rules
    $rectorConfig->sets([
        // Original rules
        LevelSetList::UP_TO_PHP_81,
        SetList::CODE_QUALITY,
        SetList::DEAD_CODE,
        SetList::TYPE_DECLARATION,

        // New rules for Symfony 7
        SymfonySetList::SYMFONY_70,
        SymfonySetList::SYMFONY_CODE_QUALITY,
        SymfonySetList::SYMFONY_CONSTRUCTOR_INJECTION,
        DoctrineSetList::DOCTRINE_CODE_QUALITY,
    ]);

    // Optionally, import names and remove unused imports
    $rectorConfig->importNames();
    $rectorConfig->removeUnusedImports();
};
