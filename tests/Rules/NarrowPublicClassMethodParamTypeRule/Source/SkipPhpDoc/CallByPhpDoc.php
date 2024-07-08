<?php

declare(strict_types=1);

namespace Rector\TypePerfect\Tests\Rules\NarrowPublicClassMethodParamTypeRule\Source\ExpectedThisType;

use Rector\TypePerfect\Tests\Rules\NarrowPublicClassMethodParamTypeRule\Fixture\SkipPhpDoc;
use Rector\TypePerfect\Tests\Rules\NarrowPublicClassMethodParamTypeRule\Fixture\SkipThisPassedExactType;

final class CallByPhpDoc
{
    /**
     * @param int $i
     */
    public function run(SkipPhpDoc $skipPhpDoc, $i): void
    {
        $skipPhpDoc->called($i);
    }
}
