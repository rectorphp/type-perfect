<?php

declare(strict_types=1);

namespace Rector\TypePerfect\Tests\Rules\ReturnNullOverFalseRule\Fixture;

final class SkipCallable
{
    public function map(callable $mapper): array
    {
        return [];
    }

    public function run()
    {
        $this->map(function () {});
    }
}
