<?php
declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\Test\InvalidationStrategy;

use Loculus\SessionSecurityBundle\Exception\InvalidSessionException;
use Loculus\SessionSecurityBundle\InvalidationStrategy\ThrowInvalidSessionExceptionStrategy;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class ThrowInvalidSessionExceptionStrategyTest extends TestCase
{
    private LoggerInterface $logger;

    protected function setUp(): void
    {
        $this->logger = $this->createMock(LoggerInterface::class);
    }

    public function testStrategy(): void
    {
        $this->expectException(InvalidSessionException::class);

        $strategy = new ThrowInvalidSessionExceptionStrategy($this->logger);

        self::assertEquals('throw_invalid_session_exception_strategy', $strategy->getName());

        $strategy->execute();
    }
}
