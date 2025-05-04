<?php

declare(strict_types=1);

namespace Rector\TypePerfect\PhpDoc;

use PhpParser\Comment\Doc;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\PhpDoc\ResolvedPhpDocBlock;
use PHPStan\Reflection\ClassReflection;

final class ApiDocStmtAnalyzer
{
    public function hasApiDoc(ClassMethod $classMethod, ClassReflection $classReflection): bool
    {
        if ($this->hasClassReflectionApiDoc($classReflection)) {
            return true;
        }

        $docComment = $classMethod->getDocComment();
        if (! $docComment instanceof Doc) {
            return false;
        }

        return strpos($docComment->getText(), '@api') !== false;
    }

    private function hasClassReflectionApiDoc(ClassReflection $classReflection): bool
    {
        if (! $classReflection->getResolvedPhpDoc() instanceof ResolvedPhpDocBlock) {
            return false;
        }

        $resolvedPhpDocBlock = $classReflection->getResolvedPhpDoc();
        return strpos($resolvedPhpDocBlock->getPhpDocString(), '@api') !== false;
    }
}
