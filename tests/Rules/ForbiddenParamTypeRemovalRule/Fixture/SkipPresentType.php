<?php

declare(strict_types=1);

namespace Rector\TypePerfect\Tests\Rules\ForbiddenParamTypeRemovalRule\Fixture;

use Rector\TypePerfect\Tests\Rules\ForbiddenParamTypeRemovalRule\Source\SomeNode;
use Rector\TypePerfect\Tests\Rules\ForbiddenParamTypeRemovalRule\Source\SomeRectorInterface;

final class SkipPresentType implements SomeRectorInterface
{
    public function refactor(SomeNode $node)
    {
    }
}
