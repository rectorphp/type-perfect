<?php

declare(strict_types=1);

namespace Rector\TypePerfect\NodeFinder;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Stmt\Class_;
use PhpParser\NodeFinder;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use Rector\TypePerfect\Printer\NodeComparator;
use Rector\TypePerfect\Reflection\ReflectionParser;
use Webmozart\Assert\Assert;

final class MethodCallNodeFinder
{
    /**
     * @readonly
     * @var \Rector\TypePerfect\Reflection\ReflectionParser
     */
    private $reflectionParser;
    /**
     * @readonly
     * @var \Rector\TypePerfect\Printer\NodeComparator
     */
    private $nodeComparator;
    /**
     * @readonly
     * @var \PhpParser\NodeFinder
     */
    private $nodeFinder;

    public function __construct(ReflectionParser $reflectionParser, NodeComparator $nodeComparator)
    {
        $this->reflectionParser = $reflectionParser;
        $this->nodeComparator = $nodeComparator;
        $this->nodeFinder = new NodeFinder();
    }

    /**
     * @return MethodCall[]
     */
    public function findUsages(MethodCall $methodCall, Scope $scope): array
    {
        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return [];
        }

        $classLike = $this->reflectionParser->parseClassReflection($classReflection);
        if (! $classLike instanceof Class_) {
            return [];
        }

        $methodCalls = $this->nodeFinder->find($classLike, function (Node $node) use ($methodCall): bool {
            if (! $node instanceof MethodCall) {
                return false;
            }

            if (! $this->nodeComparator->areNodesEqual($node->var, $methodCall->var)) {
                return false;
            }

            return $this->nodeComparator->areNodesEqual($node->name, $methodCall->name);
        });

        Assert::allIsInstanceOf($methodCalls, MethodCall::class);

        return $methodCalls;
    }
}
