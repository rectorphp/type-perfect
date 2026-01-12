<?php

declare(strict_types=1);

namespace Rector\TypePerfect\ValueObject;

final class MethodCallReference
{
    /**
     * @readonly
     */
    private string $class;
    /**
     * @readonly
     */
    private string $method;
    public function __construct(string $class, string $method)
    {
        $this->class = $class;
        $this->method = $method;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getMethod(): string
    {
        return $this->method;
    }
}
