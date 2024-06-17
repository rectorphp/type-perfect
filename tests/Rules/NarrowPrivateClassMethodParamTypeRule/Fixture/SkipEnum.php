<?php

declare(strict_types=1);

namespace Rector\TypePerfect\Tests\Rules\NarrowPrivateClassMethodParamTypeRule\Fixture;

use Rector\TypePerfect\Tests\Rules\NarrowPrivateClassMethodParamTypeRule\Source\PickReasonEnum;

class SkipEnum
{
    public function pick(PickReasonEnum $pickReasonEnum): void
    {
    }
}