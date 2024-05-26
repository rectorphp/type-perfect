<?php

declare(strict_types=1);

namespace Rector\TypePerfect;

final readonly class Configuration
{
    /**
     * @param array<string, mixed> $parameters
     */
    public function __construct(
        private array $parameters
    ) {
    }

    public function isNarrowEnabled(): bool
    {
        return $this->parameters['narrow'] ?? false;
    }

    public function isNoMixedEnabled(): bool
    {
        return $this->parameters['no_mixed'] ?? false;
    }

    public function isNoFalsyReturnEnabled(): bool
    {
        return $this->parameters['no_falsy_return'] ?? false;
    }
}
