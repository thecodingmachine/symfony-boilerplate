<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\Symfony\Set\SymfonySetList;
use Rector\CodingStyle\Rector\Use_;
use Rector\CodingStyle\Rector\ClassConst;
use Rector\CodingStyle\Rector\String_;
use Rector\CodingStyle\Rector\Encapsed;
use Rector\DeadCode\Rector\Cast;
use Rector\Symfony\Rector\Class_;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src',
    ]);

    // register a single rule
    $rectorConfig->rule(InlineConstructorDefaultToPropertyRector::class);

    // define sets of rules
    $rectorConfig->sets([
        // Update to php 8.0 standards
        SetList::PHP_80,
        LevelSetList::UP_TO_PHP_74,
        // Update to symfony 5.4 standard
        SymfonySetList::SYMFONY_54,
        SymfonySetList::SYMFONY_CODE_QUALITY,
        SymfonySetList::SYMFONY_CONSTRUCTOR_INJECTION,
    ]);
    $rectorConfig->rules([
        // Code style
        Use_\SeparateMultiUseImportsRector::class,
        ClassConst\SplitGroupedConstantsAndPropertiesRector::class,
        String_\SymplifyQuoteEscapeRector::class,
        String_\UseClassKeywordForClassNameResolutionRector::class,
        ClassConst\VarConstantCommentRector::class,
        Encapsed\WrapEncapsedVariableInCurlyBracesRector::class,
        // Dead code
        Cast\RecastingRemovalRector::class,
    ]);
    $rectorConfig->skip([
        // Ignore TDBM generated
        __DIR__ . '/src/Domain/Model/Generated',
        __DIR__ . '/src/Domain/Dao/Generated',
        __DIR__ . '/src/Domain/ResultIterator/Generated',
        // Ignore recommended symfony configuration for "Define Commands as Services" (abstract do not use php 7 standards)
        Class_\MakeCommandLazyRector::class,
    ]);
    $rectorConfig->phpVersion(PhpVersion::PHP_80);
};
