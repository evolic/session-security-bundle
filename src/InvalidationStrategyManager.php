<?php
declare(strict_types=1);

namespace Loculus\SessionSecurityBundle;

class InvalidationStrategyManager
{
    public function __construct(
        private InvalidationStrategyChain $invalidationStrategyChain,
    ) {
    }

    public function setup(array $config): void
    {
        $this->invalidationStrategyChain->setEnabledStrategies($config);
    }

    public function handle(): void
    {
        foreach ($this->invalidationStrategyChain->getEnabledStrategies() as $enabledStrategy) {
            $enabledStrategy->execute();
        }
    }
}
