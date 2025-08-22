<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = (new Finder())
    ->in(__DIR__)
    ->exclude(['vendor', 'var', 'tests/integration']);

return (new Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'phpdoc_to_comment' => false,
        'native_function_invocation' => ['include' => ['@compiler_optimized']],
        'declare_strict_types' => false,
        'array_syntax' => ['syntax' => 'short'],
    ])
    ->setFinder($finder);


