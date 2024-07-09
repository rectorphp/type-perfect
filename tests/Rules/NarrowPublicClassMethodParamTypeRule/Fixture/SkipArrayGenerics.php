<?php

declare(strict_types=1);

namespace Rector\TypePerfect\Tests\Rules\NarrowPublicClassMethodParamTypeRule\Fixture;

class SkipArrayGenerics
{
    public function run(array $aArray = [])
    {
    }

}
