<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests');

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        '@Symfony' => true, // Dodajemo Symfony pravila koja bolje razumeju #[Route]
        
        // Ključna pravila koja sprečavaju lomljenje ruta i argumenata u više redova
        'method_argument_space' => [
            'on_multiline' => 'ignore',
        ],
        'trailing_comma_in_multiline' => [
            'elements' => ['arrays'] // Sprečava dodavanje zareza u argumentima i atributima
        ],
        
        // Tvoja postojeća pravila
        'phpdoc_align' => ['align' => 'vertical'],
        'phpdoc_scalar' => true,
        'phpdoc_to_comment' => false,
        'phpdoc_no_empty_return' => true,
        'phpdoc_separation' => true,
        'phpdoc_trim' => true,
        'phpdoc_indent' => true,
        'phpdoc_add_missing_param_annotation' => true,
        'ordered_imports' => [
            'sort_algorithm' => 'length',
        ],
        'array_indentation' => true,
        'binary_operator_spaces' => [
            'default' => 'single_space',
            'operators' => ['=>' => 'align_single_space_minimal'],
        ],
        'no_unused_imports' => true
    ])
    ->setFinder($finder);