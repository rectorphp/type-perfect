<?php

declare(strict_types=1);

namespace Rector\TypePerfect\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Return_;
use PhpParser\NodeFinder;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleError;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\BooleanType;
use PHPStan\Type\Constant\ConstantBooleanType;
use Rector\TypePerfect\Configuration;

/**
 * @implements Rule<ClassMethod>
 */
final class ReturnNullOverFalseRule implements Rule
{
    /**
     * @readonly
     * @var \Rector\TypePerfect\Configuration
     */
    private $configuration;
    /**
     * @api
     * @var string
     */
    public const ERROR_MESSAGE = 'Returning false in non return bool class method. Use null with type|null instead or add bool return type';

    /**
     * @readonly
     * @var \PhpParser\NodeFinder
     */
    private $nodeFinder;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
        $this->nodeFinder = new NodeFinder();
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
     * @return RuleError[]
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if (! $this->configuration->isNoFalsyReturnEnabled()) {
            return [];
        }

        if ($node->stmts === null) {
            return [];
        }

        if ($node->returnType instanceof Node) {
            return [];
        }

        /** @var Return_[] $returns */
        $returns = $this->nodeFinder->findInstanceOf($node->stmts, Return_::class);

        $hasFalseType = false;
        $hasTrueType = false;

        foreach ($returns as $return) {
            if (! $return->expr instanceof Expr) {
                continue;
            }

            $exprType = $scope->getType($return->expr);
            if (! $exprType instanceof ConstantBooleanType) {
                if ($exprType instanceof BooleanType) {
                    return [];
                }

                continue;
            }

            if ($exprType->getValue()) {
                $hasTrueType = true;
                continue;
            }

            $hasFalseType = true;
        }

        if (! $hasTrueType && $hasFalseType) {
            return [
                RuleErrorBuilder::message(self::ERROR_MESSAGE)
                    ->identifier('typePerfect.nullOverFalse')
                    ->build(),
            ];
        }

        return [];
    }
}
