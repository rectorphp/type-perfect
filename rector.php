<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;

return RectorConfig::configure()
    ->withPhpSets()
    ->withRootFiles()
    ->withPreparedSets(
        codeQuality: true,
        deadCode: true,
        codingStyle: true,
        typeDeclarations: true,
        privatization: true,
        naming: true,
        earlyReturn: true
    )
    ->withPaths([__DIR__ . '/config', __DIR__ . '/src', __DIR__ . '/tests'])
    ->withImportNames(removeUnusedImports: true)
    ->withSkip(['*/Source/*', '*/Fixture/*', StringClassNameToClassConstantRector::class]);
