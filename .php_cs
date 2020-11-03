<?php

$finder = PhpCsFixer\Finder::create()
    // ->exclude('somedir')
    ->in(__DIR__)
;

return PhpCsFixer\Config::create()
    ->setRules([
        '@Symfony' => true,
        'full_opening_tag' => false,
    ])
    ->setFinder($finder)
;
