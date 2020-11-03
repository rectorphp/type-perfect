<?php

declare(strict_types=1);

namespace Rector\TypePerfect\Tests\Rules\ForbiddenParamTypeRemovalRule\Fixture;

use Rector\TypePerfect\Tests\Rules\ForbiddenParamTypeRemovalRule\Source\NoTypeInterface;

final class SkipNoType implements NoTypeInterface
{
    public function noType($node)
    {
    }
}
