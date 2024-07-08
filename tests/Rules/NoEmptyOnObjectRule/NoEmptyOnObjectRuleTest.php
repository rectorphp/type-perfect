<?php

declare(strict_types=1);

namespace Rector\TypePerfect\Tests\Rules\NoEmptyOnObjectRule;

use Iterator;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\TypePerfect\Rules\NoEmptyOnObjectRule;

final class NoEmptyOnObjectRuleTest extends RuleTestCase
{
    /**
     * @param mixed[] $expectedErrorMessagesWithLines
     */
    #[DataProvider('provideData')]
    public function testRule(string $filePath, array $expectedErrorMessagesWithLines): void
    {
        $this->analyse([$filePath], $expectedErrorMessagesWithLines);
    }

    public static function provideData(): Iterator
    {
        yield [__DIR__ . '/Fixture/EmptyOnObject.php', [[NoEmptyOnObjectRule::ERROR_MESSAGE, 19]]];

        yield [__DIR__ . '/Fixture/SkipEmptyOnArray.php', []];
        yield [__DIR__ . '/Fixture/SkipEmptyOnArrayNestedOnObject.php', []];
        yield [__DIR__ . '/Fixture/SkipPossibleUndefinedVariable.php', []];
        yield [__DIR__ . '/Fixture/SkipPhpDocType.php', []];
    }

    /**
     * @return string[]
     */
    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/../../../config/extension.neon'];
    }

    protected function getRule(): Rule
    {
        return self::getContainer()->getByType(NoEmptyOnObjectRule::class);
    }
}
