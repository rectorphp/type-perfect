<?php

declare(strict_types=1);

namespace Rector\TypePerfect\Rules;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\Php\PhpMethodReflection;
use PHPStan\Rules\Rule;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use Rector\TypePerfect\Reflection\MethodNodeAnalyser;

/**
 * @see \Rector\TypePerfect\Tests\Rules\ForbiddenParamTypeRemovalRule\ForbiddenParamTypeRemovalRuleTest
 * @implements Rule<ClassMethod>
 */
final class ForbiddenParamTypeRemovalRule implements Rule
{
    /**
     * @readonly
     * @var \Rector\TypePerfect\Reflection\MethodNodeAnalyser
     */
    private $methodNodeAnalyser;
    /**
     * @var string
     */
    public const ERROR_MESSAGE = 'Removing parent param type is forbidden';

    public function __construct(MethodNodeAnalyser $methodNodeAnalyser)
    {
        $this->methodNodeAnalyser = $methodNodeAnalyser;
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
     * @return string[]
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if ($node->params === []) {
            return [];
        }

        $classMethodName = (string) $node->name;
        $parentClassMethodReflection = $this->methodNodeAnalyser->matchFirstParentClassMethod($scope, $classMethodName);
        if (! $parentClassMethodReflection instanceof PhpMethodReflection) {
            return [];
        }

        foreach ($node->params as $paramPosition => $param) {
            if ($param->type !== null) {
                continue;
            }

            $parentParamType = $this->resolveParentParamType($parentClassMethodReflection, $paramPosition);
            if ($parentParamType instanceof MixedType) {
                continue;
            }

            // removed param type!
            return [self::ERROR_MESSAGE];
        }

        return [];
    }

    private function resolveParentParamType(PhpMethodReflection $phpMethodReflection, int $paramPosition): Type
    {
        foreach ($phpMethodReflection->getVariants() as $parametersAcceptorWithPhpDoc) {
            foreach ($parametersAcceptorWithPhpDoc->getParameters() as $parentParamPosition => $parameterReflectionWithPhpDoc) {
                if ($paramPosition !== $parentParamPosition) {
                    continue;
                }

                return $parameterReflectionWithPhpDoc->getNativeType();
            }
        }

        return new MixedType();
    }
}
