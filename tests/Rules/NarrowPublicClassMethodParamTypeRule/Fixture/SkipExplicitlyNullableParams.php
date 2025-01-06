<?php

declare(strict_types=1);

namespace Rector\TypePerfect\Tests\Rules\NarrowPublicClassMethodParamTypeRule\Fixture;

final class SkipExplicitlyNullableParams
{
    public function test(
        ?bool $bool = true,
        array $array = [],
    ): bool
    {
        return $bool;
    }

    static public function run(): void
    {
        $skipDateTimeMix = new SkipExplicitlyNullableParams();
        $skipDateTimeMix->test(false);
    }
}
