<?php

/*
 * (c) Tomasz Kuter <tkuter@loculus.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\Test\InvalidationStrategy;

use Loculus\SessionSecurityBundle\InvalidationStrategy\ThrowCookieTheftExceptionStrategy;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Exception\CookieTheftException;

class ThrowCookieTheftExceptionStrategyTest extends TestCase
{
    private LoggerInterface|MockObject $logger;

    protected function setUp(): void
    {
        $this->logger = $this->createMock(LoggerInterface::class);
    }

    public function testStrategy(): void
    {
        $this->expectException(CookieTheftException::class);

        $strategy = new ThrowCookieTheftExceptionStrategy($this->logger);

        self::assertEquals('throw_cookie_theft_exception_strategy', $strategy->getName());

        $strategy->execute();
    }
}
