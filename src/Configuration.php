<?php

declare(strict_types=1);

namespace Rector\TypePerfect;

final class Configuration
{
    /**
     * @var array<string, mixed>
     */
    private $parameters;
    /**
     * @param array<string, mixed> $parameters
     */
    public function __construct(
        array $parameters
    ) {
        $this->parameters = $parameters;
        // enabed by default in tests
        if (defined('PHPUNIT_COMPOSER_INSTALL')) {
            $this->parameters['narrow_param'] = true;
            $this->parameters['narrow_return'] = true;
            $this->parameters['no_mixed'] = true;
            $this->parameters['null_over_false'] = true;
        }
    }

    public function isNarrowParamEnabled(): bool
    {
        return $this->parameters['narrow_param'] ?? false;
    }

    public function isNarrowReturnEnabled(): bool
    {
        return $this->parameters['narrow_return'] ?? false;
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
