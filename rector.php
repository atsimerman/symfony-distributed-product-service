<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;

return RectorConfig::configure()
    ->withPaths([__DIR__ . '/src', __DIR__ . '/tests'])
    // Doctrine-hydrated classes (src/Entity/*) must be added here when they
    // appear: no readonly, no property removal or promotion
    // (php8 skill, references/immutability-boundaries.md).
    ->withSkip([
        __DIR__ . '/src/Kernel.php',
    ])
    ->withPhpSets()
    ->withSets([
        SetList::DEAD_CODE,
        SetList::CODE_QUALITY,
        SetList::TYPE_DECLARATION,
    ])
    ->withComposerBased(symfony: true, doctrine: true, phpunit: true)
    ->withAttributesSets(symfony: true, doctrine: true, phpunit: true);
