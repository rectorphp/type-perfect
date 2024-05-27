<?php

declare(strict_types=1);

namespace Rector\TypePerfect\Printer;

use PhpParser\Node;
use PhpParser\PrettyPrinter\Standard;

final readonly class NodeComparator
{
    public function __construct(
        private Standard $standard
    ) {
    }

    public function areNodesEqual(Node $firstNode, Node $secondNode): bool
    {
        // remove comments from nodes
        $firstNode->setAttribute('comments', null);
        $secondNode->setAttribute('comments', null);

        return $this->standard->prettyPrint([$firstNode]) === $this->standard->prettyPrint([$secondNode]);
    }
}
