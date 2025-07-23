<?php

declare(strict_types=1);

namespace Rector\TypePerfect\Tests\Rules\NarrowPrivateClassMethodParamTypeRule\Fixture;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;

class SkipVariadics
{
    public function run(Node $node, Node $node2, Node $node3)
    {
        $arr = [$node, $node2, $node3];
        $this->takesVariadic(...$arr);
    }

    private function takesVariadic(Node ...$node)
    {

    }
}
