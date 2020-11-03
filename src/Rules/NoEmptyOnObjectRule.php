<?php

declare(strict_types=1);

namespace Rector\TypePerfect\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Empty_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleError;
use PHPStan\Rules\RuleErrorBuilder;

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
     * @return RuleError[]
     */
    public function processNode(Node $node, Scope $scope): array
    {
        $expr = $node->expr;
        if ($this->shouldSkipVariable($expr, $scope)) {
            return [];
        }

        $ruleError = RuleErrorBuilder::message(self::ERROR_MESSAGE)
            ->line($node->getLine())
            ->identifier('type_perfect.no_empty_on_object')
            ->build();

        return [$ruleError];
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
