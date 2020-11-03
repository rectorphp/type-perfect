<?php

declare(strict_types=1);

namespace Rector\TypePerfect\Tests\Rules\ForbiddenParamTypeRemovalRule\Source;

interface SomeRectorInterface
{
    public function refactor(SomeNode $node);
}
