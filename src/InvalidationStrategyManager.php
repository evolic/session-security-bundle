<?php
declare(strict_types=1);

namespace Loculus\SessionSecurityBundle;

class InvalidationStrategyManager
{
    private InvalidationStrategyChain $invalidationStrategyChain;

    public function __construct(
        InvalidationStrategyChain $invalidationStrategyChain
    ) {
        $this->invalidationStrategyChain = $invalidationStrategyChain;
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
