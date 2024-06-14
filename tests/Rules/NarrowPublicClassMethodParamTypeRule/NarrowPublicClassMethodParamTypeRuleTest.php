<?php

declare(strict_types=1);

namespace Rector\TypePerfect\Tests\Rules\NarrowPublicClassMethodParamTypeRule;

use Iterator;
use PHPStan\Collectors\Collector;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\TypePerfect\Rules\NarrowPublicClassMethodParamTypeRule;
use Rector\TypePerfect\Tests\Rules\NarrowPublicClassMethodParamTypeRule\Source\ExpectedThisType\CallByThisFromInterface;

final class NarrowPublicClassMethodParamTypeRuleTest extends RuleTestCase
{
    /**
     * @param string[] $filePaths
     * @param mixed[] $expectedErrorsWithLines
     */
    #[DataProvider('provideData')]
    public function testRule(array $filePaths, array $expectedErrorsWithLines): void
    {
        $this->analyse($filePaths, $expectedErrorsWithLines);
    }

    public static function provideData(): Iterator
    {
        yield [[__DIR__ . '/Fixture/SkipNonPublicClassMethod.php'], []];

        // skip first class callables as anything can be passed there
        yield [[
            __DIR__ . '/Fixture/FirstClassCallables/CallVariadics.php',
            __DIR__ . '/Fixture/FirstClassCallables/SomeCalledMethod.php',
        ], []];

        // skip expected scalar type
        yield [[
            __DIR__ . '/Fixture/SkipProperlyFilledParamType.php',
            __DIR__ . '/Source/ExpectedType/FirstTypedCaller.php',
            __DIR__ . '/Source/ExpectedType/SecondTypedCaller.php',
        ], []];

        // skip expected object type
        yield [[
            __DIR__ . '/Fixture/SkipExpectedClassType.php',
            __DIR__ . '/Source/ExpectedClassType/FirstClassTypedCaller.php',
            __DIR__ . '/Source/ExpectedClassType/SecondClassTypedCaller.php',
        ], []];

        // skip class-string
        yield [[
            __DIR__ . '/Fixture/SkipClassStringPassed.php',
            __DIR__ . '/Source/ExpectedClassString/FirstTypedCaller.php',
            __DIR__ . '/Source/ExpectedClassString/SecondTypedCaller.php',
        ], []];

        // skip everything in case of values is mixed
        yield [[
            __DIR__ . '/Fixture/SkipMixedAndString.php',
            __DIR__ . '/Source/MixedAndString/FirstCaller.php',
            __DIR__ . '/Source/MixedAndString/SecondCaller.php',
        ], []];

        // skip int + string values
        yield [[
            __DIR__ . '/Fixture/SkipMixedAndString.php',
            __DIR__ . '/Source/MixedAndString/FirstCaller.php',
            __DIR__ . '/Source/MixedAndString/ThirdCaller.php',
        ], []];

        // skip nullable compare
        yield [[
            __DIR__ . '/Fixture/SkipNullableCompare.php',
            __DIR__ . '/Source/NullableParam/FirstNullable.php',
            __DIR__ . '/Source/NullableParam/SecondNullable.php',
        ], []];

        // skip api
        yield [[
            __DIR__ . '/Fixture/SkipApiMarked.php',
            __DIR__ . '/Source/ExpectedNodeApi/CallWithProperty.php',
        ], []];

        // skip equal union type
        yield [[
            __DIR__ . '/Fixture/SkipEqualUnionType.php',
            __DIR__ . '/Source/ExpectedUnion/CallUnionType.php',
        ], []];

        // skip equal union type flipped
        yield [[
            __DIR__ . '/Fixture/SkipEqualUnionType.php',
            __DIR__ . '/Source/ExpectedUnion/CallUnionTypeFlipped.php',
        ], []];

        // skip equal union type ternary if else
        yield [[
            __DIR__ . '/Fixture/SkipEqualUnionType.php',
            __DIR__ . '/Source/ExpectedUnion/CallUnionTypeTernaryIfElse.php',
        ], []];

        // skip equal union type ternary if else flipped
        yield [[
            __DIR__ . '/Fixture/SkipEqualUnionType.php',
            __DIR__ . '/Source/ExpectedUnion/CallUnionTypeTernaryIfElseFlipped.php',
        ], []];

        // skip equal union type array typed
        yield [[
            __DIR__ . '/Fixture/SkipEqualUnionType.php',
            __DIR__ . '/Source/ExpectedUnion/CallUnionArrayType.php',
        ], []];

        // skip this passed exact type
        yield [[
            __DIR__ . '/Fixture/SkipThisPassedExactType.php',
            __DIR__ . '/Source/ExpectedThisType/CallByThis.php',
        ], []];

        // skip used internally for second type
        yield [[
            __DIR__ . '/Fixture/SkipUsedInternallyForSecondType.php',
            __DIR__ . '/Source/ExpectedType/OnlyFirstTypeCalledOutside.php',
        ], []];

        $argErrorMessage = sprintf(NarrowPublicClassMethodParamTypeRule::ERROR_MESSAGE, 'int');
        yield [[
            __DIR__ . '/Fixture/PublicDoubleShot.php',
            __DIR__ . '/Source/FirstCaller.php',
            __DIR__ . '/Source/SecondCaller.php',
        ], [[$argErrorMessage, 9]]];

        // this passed from interface
        $argErrorMessage = sprintf(
            NarrowPublicClassMethodParamTypeRule::ERROR_MESSAGE,
            CallByThisFromInterface::class
        );
        yield [[
            __DIR__ . '/Fixture/ThisPassedFromInterface.php',
            __DIR__ . '/Source/ExpectedThisType/CallByThisFromInterface.php',
        ], [[$argErrorMessage, 11]]];

        yield [[
            __DIR__ . '/Fixture/SkipSelf.php',
        ], []];
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
        return self::getContainer()->getByType(NarrowPublicClassMethodParamTypeRule::class);
    }

    /**
     * Warning, just spent hour looking for why the test does not run :D This should be implicit part of the parent
     * class.
     *
     * @return Collector[]
     */
    protected function getCollectors(): array
    {
        return self::getContainer()->getServicesByTag('phpstan.collector');
    }
}
