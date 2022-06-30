<?php
declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\Tests;

use Loculus\SessionSecurityBundle\Exception\SessionInvalidationStrategyException;
use Loculus\SessionSecurityBundle\Exception\SessionInvalidationStrategyNotFoundException;
use Loculus\SessionSecurityBundle\InvalidationStrategy\InvalidationStrategyInterface;
use Loculus\SessionSecurityBundle\InvalidationStrategyChain;
use PHPUnit\Framework\TestCase;

class InvalidationStrategyChainTest extends TestCase
{
    public function testConstructingInvalidationStrategyChainWithNoData(): void
    {
        $invalidationStrategyChain = new InvalidationStrategyChain();

        self::assertEquals([], $invalidationStrategyChain->getEnabledStrategies());
    }

    public function testConstructingInvalidationStrategyChainWithNoDataAndEmptyListOfEnabledValidators(): void
    {
        $invalidationStrategyChain = new InvalidationStrategyChain();

        $invalidationStrategyChain->setEnabledStrategies([]);

        self::assertCount(0, $invalidationStrategyChain->getStrategies());
        self::assertEquals([], $invalidationStrategyChain->getEnabledStrategies());
    }

    public function testConstructingInvalidationStrategyChainWithAnonymousValidator(): void
    {
        $strategy = $this->getEmptyInvalidationStrategy();

        $invalidationStrategyChain = new InvalidationStrategyChain($strategy);

        $invalidationStrategyChain->setEnabledStrategies(['empty_strategy']);

        $enabledStrategies = $invalidationStrategyChain->getEnabledStrategies();

        self::assertCount(1, $invalidationStrategyChain->getStrategies());

        foreach ($enabledStrategies as $enabledStrategy) {
            self::assertInstanceOf(InvalidationStrategyInterface::class, $enabledStrategy);
        }
    }

    public function testEnablingStrategyWhichIsUnknownToInvalidationStrategyChain(): void
    {
        self::expectException(SessionInvalidationStrategyException::class);

        $strategy = $this->getEmptyInvalidationStrategy();

        $invalidationStrategyChain = new InvalidationStrategyChain($strategy);

        $invalidationStrategyChain->setEnabledStrategies(['unknown_strategy']);
    }

    public function testGettingStrategyWhichIsKnownToInvalidationStrategyChain(): void
    {
        $strategy = $this->getEmptyInvalidationStrategy();

        $invalidationStrategyChain = new InvalidationStrategyChain($strategy);

        self::assertInstanceOf(
            InvalidationStrategyInterface::class,
            $invalidationStrategyChain->get($strategy->getName())
        );
    }

    public function testGettingStrategyWhichIsUnknownToInvalidationStrategyChain(): void
    {
        self::expectException(SessionInvalidationStrategyNotFoundException::class);

        $strategy = $this->getEmptyInvalidationStrategy();

        $invalidationStrategyChain = new InvalidationStrategyChain($strategy);

        $invalidationStrategyChain->get('unknown_strategy');
    }

    private function getEmptyInvalidationStrategy(): InvalidationStrategyInterface
    {
        return new class() implements InvalidationStrategyInterface
        {
            private const NAME = 'empty_strategy';

            public function getName(): string
            {
                return self::NAME;
            }

            public function execute(): void
            {
            }
        };
    }
}
