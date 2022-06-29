<?php
declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\Tests\Event;

use Loculus\SessionSecurityBundle\Event\InvalidSessionEvent;
use PHPUnit\Framework\TestCase;

class InvalidSessionEventTest extends TestCase
{
    public function testConstructingInvalidSessionEvent(): void
    {
        $invalidationType = 'user_agent_validator';
        $event = new InvalidSessionEvent($invalidationType);

        self::assertEquals($invalidationType, $event->getInvalidationType());
    }
}
