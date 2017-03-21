<?php

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => false,
        'array_syntax' => false,
        'blank_line_after_namespace' => false,
        'braces' => false,
        'class_definition' => false,
        'combine_consecutive_unsets' => false,
        'concat_space' => false,
        'hash_to_slash_comment' => false,
        'method_argument_space' => false,
        'method_separation' => false,
        'native_function_casing' => false,
        'normalize_index_brace' => false,
        'no_extra_consecutive_blank_lines' => ['extra', 'use'],
        'no_empty_comment' => false,
        'no_empty_phpdoc' => false,
        'no_empty_statement' => false,
        'no_unused_imports' => false,
        'no_useless_else' => false,
        'no_useless_return' => false,
        'no_multiline_whitespace_before_semicolons' => false,
        'no_spaces_around_offset' => false,
        'no_trailing_whitespace_in_comment' => false,
        'no_whitespace_in_blank_line' => false,
        'ordered_class_elements' => false,
        'ordered_imports' => false,
        'php_unit_fqcn_annotation' => false,
        'php_unit_strict' => false,
        'phpdoc_align' => false,
        'phpdoc_indent' => false,
        'phpdoc_no_alias_tag' => false,
        'phpdoc_annotation_without_dot' => false,
        'phpdoc_add_missing_param_annotation' => false,
        'phpdoc_no_useless_inheritdoc' => false,
        'phpdoc_single_line_var_spacing' => false,
        'phpdoc_trim' => false,
        'psr4' => true,
        'short_scalar_cast' => false,
        'space_after_semicolon' => false,
        'strict_comparison' => false,
        'strict_param' => false,
        'switch_case_semicolon_to_colon' => false,
        'switch_case_space' => false,
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->notName('barclays-callback-response.xml.twig')
            ->notName('base_ubl_test.php.twig')
            ->notName('entity.php.twig')
            ->notName('test.php.twig')
            ->notName('ProductInformation.xml.twig')
            ->notName('check.php')
            ->notName('SymfonyRequirements.php')
            ->exclude(['bin', 'ddl', 'features', 'vendor', 'app/cache'])
            ->in(__DIR__)
    );
