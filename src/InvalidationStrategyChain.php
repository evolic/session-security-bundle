<?php
declare(strict_types=1);

namespace Loculus\SessionSecurityBundle;

use Loculus\SessionSecurityBundle\Exception\SessionInvalidationStrategyException;
use Loculus\SessionSecurityBundle\InvalidationStrategy\InvalidationStrategyInterface;

class InvalidationStrategyChain
{
    /**
     * @var array|InvalidationStrategyInterface[]
     */
    private array $strategies;

    /**
     * @var array|InvalidationStrategyInterface[]
     */
    private array $enabledStrategies = [];

    public function __construct(InvalidationStrategyInterface ...$strategies)
    {
        $this->strategies = $strategies;
    }
    
    public function getStrategies(): array
    {
        return $this->strategies;
    }

    public function setEnabledStrategies(array $enabledStrategyNames): void
    {
        foreach ($enabledStrategyNames as $enabledStrategyName) {
            $found = false;

            foreach ($this->strategies as $strategy) {
                if ($strategy->getName() === $enabledStrategyName) {
                    $this->enabledStrategies[] = $strategy;

                    $found = true;
                }
            }

            if (!$found) {
                throw new SessionInvalidationStrategyException(
                    sprintf('Cannot enable session invalidation strategy described as "%s".', $enabledStrategyName)
                );
            }
        }
    }

    public function getEnabledStrategies(): array
    {
        return $this->enabledStrategies;
    }

    public function get(string $strategyName): InvalidationStrategyInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->getName() === $strategyName) {
                return $strategy;
            }
        }
        
        throw new SessionInvalidationStrategyException(
            sprintf('Cannot find session invalidation strategy described as "%s".', $strategyName)
        );
    }
}
