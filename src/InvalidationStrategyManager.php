<?php

/*
 * (c) Tomasz Kuter <tkuter@loculus.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
