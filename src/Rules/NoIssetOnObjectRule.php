<?php

declare(strict_types=1);

namespace Rector\TypePerfect\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr\Isset_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use Rector\TypePerfect\Guard\EmptyIssetGuard;

/**
 * @see \Rector\TypePerfect\Tests\Rules\NoIssetOnObjectRule\NoIssetOnObjectRuleTest
 * @implements Rule<Isset_>
 */
final class NoIssetOnObjectRule implements Rule
{
    /**
     * @readonly
     * @var \Rector\TypePerfect\Guard\EmptyIssetGuard
     */
    private $emptyIssetGuard;
    /**
     * @var string
     */
    public const ERROR_MESSAGE = 'Use instanceof instead of isset() on object';

    public function __construct(EmptyIssetGuard $emptyIssetGuard)
    {
        $this->emptyIssetGuard = $emptyIssetGuard;
    }

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
            if ($this->emptyIssetGuard->isLegal($var, $scope)) {
                continue;
            }

            return [
                self::ERROR_MESSAGE,
            ];
        }

        return [];
    }
}
