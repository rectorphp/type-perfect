<?php

declare(strict_types=1);

namespace Rector\TypePerfect\Tests\Rules\NoReturnFalseInNonBoolClassMethodRule;

use Iterator;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\TypePerfect\Rules\NoReturnFalseInNonBoolClassMethodRule;

final class NoReturnFalseInNonBoolClassMethodRuleTest extends RuleTestCase
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
            [[NoReturnFalseInNonBoolClassMethodRule::ERROR_MESSAGE, 9]],
        ];

        yield [__DIR__ . '/Fixture/SkipReturnBool.php', []];
    }

    /**
     * @return string[]
     */
    public static function getAdditionalConfigFiles(): array
    {
        return [
            __DIR__ . '/../../../config/extension.neon',
        ];
    }

    protected function getRule(): Rule
    {
        return self::getContainer()->getByType(NoReturnFalseInNonBoolClassMethodRule::class);
    }
}
