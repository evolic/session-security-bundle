<?php
declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\Test\InvalidationStrategy;

use Loculus\SessionSecurityBundle\Exception\InvalidSessionException;
use Loculus\SessionSecurityBundle\InvalidationStrategy\ThrowInvalidSessionExceptionStrategy;
use PHPUnit\Framework\TestCase;

class ThrowInvalidSessionExceptionStrategyTest extends TestCase
{
    public function testStrategy(): void
    {
        $this->expectException(InvalidSessionException::class);

        $strategy = new ThrowInvalidSessionExceptionStrategy();

        self::assertEquals('throw_invalid_session_exception_strategy', $strategy->getName());

        $strategy->execute();
    }
}
