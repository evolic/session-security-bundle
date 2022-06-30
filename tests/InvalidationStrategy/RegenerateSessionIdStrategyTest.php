<?php
declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\Test\InvalidationStrategy;

use Loculus\SessionSecurityBundle\InvalidationStrategy\RegenerateSessionIdStrategy;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class RegenerateSessionIdStrategyTest extends TestCase
{
    private LoggerInterface|MockObject $logger;

    protected function setUp(): void
    {
        $this->logger = $this->createMock(LoggerInterface::class);
    }

    public function testStrategy(): void
    {
        $this->logger->expects(self::never())
            ->method('error')
        ;

        $strategy = new RegenerateSessionIdStrategy($this->logger);

        self::assertEquals('session_regenerate_id_strategy', $strategy->getName());

        $strategy->execute();
    }
}
