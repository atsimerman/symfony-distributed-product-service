<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->in([__DIR__ . '/src', __DIR__ . '/tests', __DIR__ . '/config', __DIR__ . '/migrations', __DIR__ . '/public'])
    ->notPath([
        'bundles.php',
        'reference.php',
    ])
    ->append([__FILE__, __DIR__ . '/rector.php']);

return new PhpCsFixer\Config()
    ->setRiskyAllowed(true)
    ->setCacheFile(__DIR__ . '/.php-cs-fixer.cache')
    ->setRules([
        '@PER-CS' => true,
        '@PER-CS:risky' => true,
        '@PHP8x5Migration' => true,
        'declare_strict_types' => true,
        'strict_param' => true,
        'ordered_imports' => ['sort_algorithm' => 'alpha', 'imports_order' => ['class', 'function', 'const']],
        'no_unused_imports' => true,
        'trailing_comma_in_multiline' => true,
    ])
    ->setFinder($finder);
