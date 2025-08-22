<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = (new Finder())
    ->in(__DIR__)
    ->exclude(['vendor', 'var', 'tests/integration']);

return (new Config())
    ->setRiskyAllowed(true)
    ->setUsingCache(true)
    ->setCacheFile(__DIR__.'/.php-cs-fixer.cache')
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'php_unit_method_casing' => ['case' => 'snake_case'],
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'phpdoc_to_comment' => false,
        'native_function_invocation' => ['include' => ['@compiler_optimized']],
        'declare_strict_types' => false,
        'array_syntax' => ['syntax' => 'short'],
        'yoda_style' => true,
        'control_structure_continuation_position' => ['position' => 'same_line'],
        'no_superfluous_elseif' => true,
        'nullable_type_declaration_for_default_null_value' => true,
    ])
    ->setFinder($finder);


