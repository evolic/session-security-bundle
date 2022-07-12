<?php

/*
 * (c) Tomasz Kuter <tkuter@loculus.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\Tests;

use Generator;
use Loculus\SessionSecurityBundle\InvalidationStrategy\InvalidationStrategyInterface;
use Loculus\SessionSecurityBundle\InvalidationStrategyChain;
use Loculus\SessionSecurityBundle\InvalidationStrategyManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class InvalidationStrategyManagerTest extends TestCase
{
    private InvalidationStrategyChain|MockObject $invalidationStrategyChain;
    private InvalidationStrategyManager $invalidationStrategyManager;

    protected function setUp(): void
    {
        $this->invalidationStrategyChain = $this->getMockBuilder(InvalidationStrategyChain::class)
            ->setConstructorArgs([
                $this->getFirstInvalidationStrategy(),
                $this->getSecondInvalidationStrategy(),
            ])
            ->getMock()
        ;

        $this->invalidationStrategyManager = new InvalidationStrategyManager($this->invalidationStrategyChain);
    }

    public function provideDataForSetupTest(): Generator
    {
        $strategy1 = $this->getFirstInvalidationStrategy();

        yield [
            [
                $strategy1->getName(),
            ]
        ];

        $strategy2 = $this->getSecondInvalidationStrategy();

        yield [
            [
                $strategy1->getName(),
                $strategy2->getName(),
            ]
        ];
    }

    /**
     * @dataProvider provideDataForSetupTest
     */
    public function testSetup(array $enabledStrategies): void
    {
        $this->invalidationStrategyChain->expects(self::once())
            ->method('setEnabledStrategies')
            ->with($enabledStrategies)
        ;

        $this->invalidationStrategyManager->setup($enabledStrategies);
    }

    public function provideDataForHandleTest(): Generator
    {
        $strategy1 = $this->createMock(InvalidationStrategyInterface::class);
        $strategy1->expects(self::once())
            ->method('execute')
        ;

        yield [
            [
                $strategy1,
            ]
        ];

        $strategy2 = $this->createMock(InvalidationStrategyInterface::class);
        $strategy2->expects(self::once())
            ->method('execute')
        ;
        $strategy3 = $this->createMock(InvalidationStrategyInterface::class);
        $strategy3->expects(self::once())
            ->method('execute')
        ;

        yield [
            [
                $strategy2,
                $strategy3,
            ]
        ];
    }

    /**
     * @dataProvider provideDataForHandleTest
     */
    public function testHandle(array $strategies): void
    {
        $this->invalidationStrategyChain->expects(self::once())
            ->method('getEnabledStrategies')
            ->willReturn($strategies)
        ;

        $this->invalidationStrategyManager->handle();
    }

    private function getFirstInvalidationStrategy(): InvalidationStrategyInterface
    {
        return new class() implements InvalidationStrategyInterface
        {
            private const NAME = 'first_strategy';

            public function getName(): string
            {
                return self::NAME;
            }

            public function execute(): void
            {
            }
        };
    }

    private function getSecondInvalidationStrategy(): InvalidationStrategyInterface
    {
        return new class() implements InvalidationStrategyInterface
        {
            private const NAME = 'second_strategy';

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
