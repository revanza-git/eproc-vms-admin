<?php

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;
use Rector\CodeQuality\Rector\If_\SimplifyIfReturnBoolRector;
use Rector\CodeQuality\Rector\FuncCall\RemoveSoleValueSprintfRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector;
use Rector\EarlyReturn\Rector\If_\ChangeNestedIfsToEarlyReturnRector;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector;

return static function (RectorConfig $rectorConfig): void {
    // Paths to refactor
    $rectorConfig->paths([
        __DIR__ . '/application/modules',  // Adjust the path to where your PHP files are
    ]);

    // Define what rule sets will be applied
    $rectorConfig->sets([
        SetList::CODE_QUALITY,
        SetList::CODING_STYLE,
        SetList::DEAD_CODE,
        SetList::EARLY_RETURN,
        SetList::TYPE_DECLARATION,
        SetList::PHP_74,
        SetList::PRIVATIZATION,
        SetList::NAMING,
    ]);

    // Adding specific rules
    $rectorConfig->rule(SimplifyIfReturnBoolRector::class);
    $rectorConfig->rule(RemoveSoleValueSprintfRector::class);
    $rectorConfig->rule(RemoveUnusedPrivateMethodRector::class);
    $rectorConfig->rule(ChangeNestedIfsToEarlyReturnRector::class);
    $rectorConfig->rule(TypedPropertyFromStrictConstructorRector::class);

    // Example of adding a specific rule, uncomment and replace SomeSpecificRector with actual rector class
    // $rectorConfig->ruleWithConfiguration(SomeSpecificRector::class, $configurationArray);

    // Skip paths or files
    // $rectorConfig->skip([
    //     __DIR__ . '/src/Legacy',
    //     __DIR__ . '/tests/SomeLegacyTest.php',
    // ]);
};
