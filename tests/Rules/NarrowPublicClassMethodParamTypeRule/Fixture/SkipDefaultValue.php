<?php

declare(strict_types=1);

namespace Rector\TypePerfect\Tests\Rules\NarrowPublicClassMethodParamTypeRule\Fixture;

class SkipDefaultValue {
    public function takesUnion(
        int|string $value = "hallo"
    ): bool
    {
        return true;
    }

    static public function run(): void
    {
        $o = new SkipDefaultValue();
        $o->takesUnion(1);
    }
}
