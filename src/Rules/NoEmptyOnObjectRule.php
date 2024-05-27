<?php

declare(strict_types=1);

namespace Rector\TypePerfect\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Empty_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<Empty_>
 */
final class NoEmptyOnObjectRule implements Rule
{
    /**
     * @var string
     */
    public const ERROR_MESSAGE = 'Use instanceof instead of empty() on object';

    public function getNodeType(): string
    {
        return Empty_::class;
    }

    /**
     * @param Empty_ $node
     * @return string[]
     */
    public function processNode(Node $node, Scope $scope): array
    {
        $expr = $node->expr;
        if ($this->shouldSkipVariable($expr, $scope)) {
            return [];
        }

        return [
            self::ERROR_MESSAGE,
        ];
    }

    private function shouldSkipVariable(Expr $expr, Scope $scope): bool
    {
        if ($expr instanceof ArrayDimFetch) {
            return true;
        }

        $varType = $scope->getType($expr);
        return $varType->getObjectClassNames() === [];
    }
}
