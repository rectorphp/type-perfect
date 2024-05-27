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
            $this->parameters['narrow'] = true;
            $this->parameters['no_mixed'] = true;
            $this->parameters['no_falsy_return'] = true;
        }
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
