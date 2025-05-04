<?php

namespace Rector\TypePerfect\Tests\Rules\NarrowPublicClassMethodParamTypeRule\Fixture\Generics;

/**
 * @template T
 */
class GenericA
{
}

class User {
    /** @param GenericA<string> $g */
    public function doFoo(GenericA $g):void {

    }
}

/** @param GenericA<string> $g */
function doFoo($g):void {
    $user = new User();
    $user->doFoo($g);
}
