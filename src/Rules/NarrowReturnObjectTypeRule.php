<?php

declare(strict_types=1);

namespace Rector\TypePerfect\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Rules\Rule;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeWithClassName;
use Rector\TypePerfect\Configuration;
use Rector\TypePerfect\NodeFinder\ReturnNodeFinder;
use Rector\TypePerfect\Reflection\MethodNodeAnalyser;

/**
 * @see \Rector\TypePerfect\Tests\Rules\NarrowReturnObjectTypeRule\NarrowReturnObjectTypeRuleTest
 *
 * @implements Rule<ClassMethod>
 */
final readonly class NarrowReturnObjectTypeRule implements Rule
{
    /**
     * @var string
     */
    public const ERROR_MESSAGE = 'Provide more specific return type "%s" over abstract one';

    public function __construct(
        private ReturnNodeFinder $returnNodeFinder,
        private MethodNodeAnalyser $methodNodeAnalyser,
        private Configuration $configuration
    ) {
    }

    /**
     * @return class-string<Node>
     */
    public function getNodeType(): string
    {
        return ClassMethod::class;
    }

    /**
     * @param ClassMethod $node
     * @return mixed[]
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if (! $this->configuration->isNarrowEnabled()) {
            return [];
        }

        if (! $node->returnType instanceof FullyQualified) {
            return [];
        }

        if ($this->shouldSkipScope($scope)) {
            return [];
        }

        $returnObjectType = new ObjectType($node->returnType->toString());
        if ($this->shouldSkipReturnObjectType($returnObjectType)) {
            return [];
        }

        $methodName = $node->name->toString();
        if ($this->methodNodeAnalyser->hasParentVendorLock($scope, $methodName)) {
            return [];
        }

        $returnExpr = $this->returnNodeFinder->findOnlyReturnsExpr($node);
        if (! $returnExpr instanceof Expr) {
            return [];
        }

        $returnExprType = $scope->getType($returnExpr);
        if ($this->shouldSkipReturnExprType($returnExprType)) {
            return [];
        }

        if ($returnObjectType->equals($returnExprType)) {
            return [];
        }

        // is subtype?
        if (! $returnObjectType->isSuperTypeOf($returnExprType)->yes()) {
            return [];
        }

        /** @var TypeWithClassName $returnExprType */
        $errorMessage = sprintf(self::ERROR_MESSAGE, $returnExprType->getClassName());
        return [$errorMessage];
    }

    private function shouldSkipReturnObjectType(ObjectType $objectType): bool
    {
        $classReflection = $objectType->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return true;
        }

        // cannot be more precise if final class
        return $classReflection->isFinal();
    }

    private function shouldSkipScope(Scope $scope): bool
    {
        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return true;
        }

        return ! $classReflection->isClass();
    }

    private function shouldSkipReturnExprType(Type $type): bool
    {
        if (! $type instanceof TypeWithClassName) {
            return true;
        }

        $classReflection = $type->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return true;
        }

        return $classReflection->isAnonymous();
    }
}
