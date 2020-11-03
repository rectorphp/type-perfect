<?php

declare(strict_types=1);

namespace Rector\TypePerfect\Tests\Rules\RequireSpecificReturnTypeOverAbstractRule\Fixture;

use Rector\TypePerfect\Tests\Rules\RequireSpecificReturnTypeOverAbstractRule\Source\AbstractControl;
use Rector\TypePerfect\Tests\Rules\RequireSpecificReturnTypeOverAbstractRule\Source\SpecificControl;

final class SomeAbstractReturnType
{
    public function create(): AbstractControl
    {
        return new SpecificControl();
    }
}
