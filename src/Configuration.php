<?php

declare(strict_types=1);

namespace Rector\TypePerfect;

final class Configuration
{
    /**
     * @param array<string, mixed> $parameters
     */
    public function __construct(
        private array $parameters
    ) {
        // enabed by default in tests
        if (defined('PHPUNIT_COMPOSER_INSTALL')) {
            $this->parameters['narrow_param'] = true;
            $this->parameters['no_mixed'] = true;
            $this->parameters['null_over_false'] = true;
        }
    }

    public function isNarrowEnabled(): bool
    {
        return $this->parameters['narrow_param'] ?? false;
    }

    public function isNoMixedEnabled(): bool
    {
        return $this->parameters['no_mixed'] ?? false;
    }

    public function isNoFalsyReturnEnabled(): bool
    {
        return $this->parameters['null_over_false'] ?? false;
    }
}
