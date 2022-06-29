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

    public function __construct(InvalidationStrategyInterface ...$strategies)
    {
        $this->strategies = $strategies;
    }
    
    public function getStrategies(): array
    {
        return $this->strategies;
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
