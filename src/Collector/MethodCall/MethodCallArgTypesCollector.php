<?php

declare(strict_types=1);

namespace Rector\TypePerfect\Collector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Collectors\Collector;
use Rector\TypePerfect\Matcher\ClassMethodCallReferenceResolver;
use Rector\TypePerfect\Printer\CollectorMetadataPrinter;
use Rector\TypePerfect\ValueObject\MethodCallReference;

/**
 * @implements Collector<MethodCall, array<string>|null>
 */
final class MethodCallArgTypesCollector implements Collector
{
    public function __construct(
        private readonly ClassMethodCallReferenceResolver $classMethodCallReferenceResolver,
        private readonly CollectorMetadataPrinter $collectorMetadataPrinter,
    ) {
    }

    public function getNodeType(): string
    {
        return MethodCall::class;
    }

    /**
     * @param MethodCall $node
     * @return array{string, string}|null
     */
    public function processNode(Node $node, Scope $scope): ?array
    {
        if ($node->getArgs() === []) {
            return null;
        }

        $classMethodCallReference = $this->classMethodCallReferenceResolver->resolve($node, $scope, true);
        if (! $classMethodCallReference instanceof MethodCallReference) {
            return null;
        }

        $classMethodReference = $this->createClassMethodReference($classMethodCallReference);

        $stringArgTypesString = $this->collectorMetadataPrinter->printArgTypesAsString($node, $scope);
        return [$classMethodReference, $stringArgTypesString];
    }

    private function createClassMethodReference(MethodCallReference $classMethodCallReference): string
    {
        $className = $classMethodCallReference->getClass();
        $methodName = $classMethodCallReference->getMethod();

        return $className . '::' . $methodName;
    }
}
