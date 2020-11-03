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
        if ($classReflection->getResolvedPhpDoc() instanceof ResolvedPhpDocBlock) {
            $resolvedPhpDoc = $classReflection->getResolvedPhpDoc();
            if (str_contains($resolvedPhpDoc->getPhpDocString(), '@api')) {
                return true;
            }
        }

        $docComment = $classMethod->getDocComment();
        if (! $docComment instanceof Doc) {
            return false;
        }

        return str_contains($docComment->getText(), '@api');
    }
}
