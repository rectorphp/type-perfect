<?php

declare(strict_types=1);

namespace Rector\TypePerfect\Reflection;

use Nette\Utils\FileSystem;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\NodeFinder;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use PHPStan\Reflection\ClassReflection;
use Throwable;

final class ReflectionParser
{
    /**
     * @var array<string, ClassLike>
     */
    private $classesByFilename = [];

    /**
     * @readonly
     * @var \PhpParser\Parser
     */
    private $parser;

    public function __construct()
    {
        $parserFactory = new ParserFactory();
        $this->parser = $parserFactory->create(ParserFactory::PREFER_PHP7);
    }

    public function parseClassReflection(ClassReflection $classReflection): ?ClassLike
    {
        $fileName = $classReflection->getFileName();
        if ($fileName === null) {
            return null;
        }

        return $this->parseFilenameToClass($fileName);
    }

    private function parseFilenameToClass(string $fileName): ?\PhpParser\Node\Stmt\ClassLike
    {
        if (isset($this->classesByFilename[$fileName])) {
            return $this->classesByFilename[$fileName];
        }

        try {
            $stmts = $this->parser->parse(FileSystem::read($fileName));
            if (! is_array($stmts)) {
                return null;
            }

            // complete namespacedName variables
            $nodeTraverser = new NodeTraverser();
            $nodeTraverser->addVisitor(new NameResolver());
            $nodeTraverser->traverse($stmts);
        } catch (Throwable $exception) {
            // not reachable
            return null;
        }

        $classLike = $this->findFirstClassLike($stmts);
        if (! $classLike instanceof ClassLike) {
            return null;
        }

        $this->classesByFilename[$fileName] = $classLike;

        return $classLike;
    }

    /**
     * @param Node[] $nodes
     */
    private function findFirstClassLike(array $nodes): ?ClassLike
    {
        $nodeFinder = new NodeFinder();

        $foundClassLike = $nodeFinder->findFirstInstanceOf($nodes, ClassLike::class);
        if ($foundClassLike instanceof ClassLike) {
            return $foundClassLike;
        }

        return null;
    }
}
