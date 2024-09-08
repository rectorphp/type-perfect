<?php

declare(strict_types=1);

namespace Rector\TypePerfect\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\PrettyPrinter\Standard;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Type\MixedType;
use Rector\TypePerfect\Configuration;

/**
 * @see \Rector\TypePerfect\Tests\Rules\NoMixedPropertyFetcherRule\NoMixedPropertyFetcherRuleTest
 * @implements Rule<PropertyFetch>
 */
final readonly class NoMixedPropertyFetcherRule implements Rule
{
    /**
     * @var string
     */
    public const ERROR_MESSAGE = 'Mixed property fetch in a "%s->..." can skip important errors. Make sure the type is known';

    public function __construct(
        private Standard $standard,
        private Configuration $configuration,
    ) {
    }

    /**
     * @return class-string<Node>
     */
    public function getNodeType(): string
    {
        return PropertyFetch::class;
    }

    /**
     * @param PropertyFetch $node
     * @return mixed[]
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if (! $this->configuration->isNoMixedPropertyEnabled()) {
            return [];
        }

        $callerType = $scope->getType($node->var);
        if (! $callerType instanceof MixedType) {
            return [];
        }

        $printedVar = $this->standard->prettyPrintExpr($node->var);

        return [sprintf(self::ERROR_MESSAGE, $printedVar)];
    }
}
