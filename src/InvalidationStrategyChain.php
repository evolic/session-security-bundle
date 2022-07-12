<?php

/*
 * (c) Tomasz Kuter <tkuter@loculus.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Loculus\SessionSecurityBundle;

use Loculus\SessionSecurityBundle\Exception\SessionInvalidationStrategyException;
use Loculus\SessionSecurityBundle\Exception\SessionInvalidationStrategyNotFoundException;
use Loculus\SessionSecurityBundle\InvalidationStrategy\InvalidationStrategyInterface;

class InvalidationStrategyChain
{
    private const ERROR_MESSAGE_CANT_ENABLE_STRATEGY = 'Cannot enable session invalidation strategy described as "%s".';
    private const ERROR_MESSAGE_CANT_FIND_STRATEGY = 'Cannot find session invalidation strategy described as "%s".';

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
                    sprintf(self::ERROR_MESSAGE_CANT_ENABLE_STRATEGY, $enabledStrategyName)
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

        throw new SessionInvalidationStrategyNotFoundException(
            sprintf(self::ERROR_MESSAGE_CANT_FIND_STRATEGY, $strategyName)
        );
    }
}
