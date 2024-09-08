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
final class NoMixedPropertyFetcherRule implements Rule
{
    /**
     * @readonly
     * @var \PhpParser\PrettyPrinter\Standard
     */
    private $standard;
    /**
     * @readonly
     * @var \Rector\TypePerfect\Configuration
     */
    private $configuration;
    /**
     * @var string
     */
    public const ERROR_MESSAGE = 'Mixed property fetch in a "%s->..." can skip important errors. Make sure the type is known';

    public function __construct(Standard $standard, Configuration $configuration)
    {
        $this->standard = $standard;
        $this->configuration = $configuration;
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
