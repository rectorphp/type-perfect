<?php

declare(strict_types=1);

namespace Rector\TypePerfect\Tests\Rules\ForbiddenParamTypeRemovalRule\Fixture;

class HasDifferentParameterWithInterfaceMethod implements AnInterface, AnInterfaceOther
{
    public function execute($string, $int)
    {
    }

    public function run($stdClass)
    {
    }
}
