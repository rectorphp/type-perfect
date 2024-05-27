<?php

declare(strict_types=1);

namespace Rector\TypePerfect\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Isset_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;

/**
 * @see \Rector\TypePerfect\Tests\Rules\NoIssetOnObjectRule\NoIssetOnObjectRuleTest
 * @implements Rule<Isset_>
 */
final class NoIssetOnObjectRule implements Rule
{
    /**
     * @var string
     */
    public const ERROR_MESSAGE = 'Use instanceof instead of isset() on object';

    public function getNodeType(): string
    {
        return Isset_::class;
    }

    /**
     * @param Isset_ $node
     *
     * @return string[]
     */
    public function processNode(Node $node, Scope $scope): array
    {
        foreach ($node->vars as $var) {
            if ($this->shouldSkipVariable($var, $scope)) {
                continue;
            }

            return [
                self::ERROR_MESSAGE,
            ];
        }

        return [];
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
