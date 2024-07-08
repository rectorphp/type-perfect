<?php

declare(strict_types=1);

namespace Rector\TypePerfect\Tests\Rules\NarrowPublicClassMethodParamTypeRule\Fixture;

use PhpParser\Node;

final class SkipPhpDoc
{
    public function called($node)
    {
    }
}
