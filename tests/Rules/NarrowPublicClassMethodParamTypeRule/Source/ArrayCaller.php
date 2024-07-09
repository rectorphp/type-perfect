<?php

declare(strict_types=1);

namespace Rector\TypePerfect\Tests\Rules\NarrowPublicClassMethodParamTypeRule\Source;

use Rector\TypePerfect\Tests\Rules\NarrowPublicClassMethodParamTypeRule\Fixture\PublicDoubleShot;
use Rector\TypePerfect\Tests\Rules\NarrowPublicClassMethodParamTypeRule\Fixture\SkipArrayGenerics;

final class ArrayCaller
{
    public function doRun(SkipArrayGenerics $r) {
        $r->run(['hello' => 'abc']);
    }

    public function doRun2(SkipArrayGenerics $r) {
        $r->run();
    }

}
