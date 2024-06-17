<?php

declare(strict_types=1);

namespace Rector\TypePerfect\Tests\Rules\ReturnNullOverFalseRule;

use Iterator;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\TypePerfect\Rules\ReturnNullOverFalseRule;

final class ReturnNullOverFalseRuleTest extends RuleTestCase
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
        yield [
            __DIR__ . '/Fixture/ReturnFalseOnly.php',
            [[ReturnNullOverFalseRule::ERROR_MESSAGE, 9]],
        ];
        yield [__DIR__ . '/Fixture/CheckResultFromOtherMethod.php', []];
        yield [__DIR__ . '/Fixture/CheckResultFromOtherMethod2.php', []];

        yield [__DIR__ . '/Fixture/SkipReturnBool.php', []];
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
        return self::getContainer()->getByType(ReturnNullOverFalseRule::class);
    }
}
