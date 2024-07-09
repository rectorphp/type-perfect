<?php

declare(strict_types=1);

namespace Rector\TypePerfect\Tests\Rules\NoParamTypeRemovalRule\Fixture;

class SkipConstruct extends ParentClass
{
    public function __construct($someArg, $someOther)
    {
        parent::__construct('foo', 4);
    }
}

class ParentClass
{
    public function __construct(string $string, int $int)
    {
    }
}
