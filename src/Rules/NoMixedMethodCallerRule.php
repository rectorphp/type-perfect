<?php

declare(strict_types=1);

namespace Rector\TypePerfect\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\PrettyPrinter\Standard;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Type\ErrorType;
use PHPStan\Type\MixedType;
use Rector\TypePerfect\Configuration;

/**
 * @see \Rector\TypePerfect\Tests\Rules\NoMixedMethodCallerRule\NoMixedMethodCallerRuleTest
 * @implements Rule<MethodCall>
 */
final class NoMixedMethodCallerRule implements Rule
{
    /**
     * @readonly
     * @var \PhpParser\PrettyPrinter\Standard
     */
    private $printerStandard;
    /**
     * @readonly
     * @var \Rector\TypePerfect\Configuration
     */
    private $configuration;
    /**
     * @var string
     */
    public const ERROR_MESSAGE = 'Mixed variable in a `%s->...()` can skip important errors. Make sure the type is known';

    public function __construct(Standard $printerStandard, Configuration $configuration)
    {
        $this->printerStandard = $printerStandard;
        $this->configuration = $configuration;
    }

    /**
     * @return class-string<Node>
     */
    public function getNodeType(): string
    {
        return MethodCall::class;
    }

    /**
     * @param MethodCall $node
     * @return mixed[]
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if (! $this->configuration->isNoMixedEnabled()) {
            return [];
        }

        $callerType = $scope->getType($node->var);
        if (! $callerType instanceof MixedType) {
            return [];
        }

        if ($callerType instanceof ErrorType) {
            return [];
        }

        $printedMethodCall = $this->printerStandard->prettyPrintExpr($node->var);

        return [
            sprintf(self::ERROR_MESSAGE, $printedMethodCall),
        ];
    }
}
