<?php
declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\Tests\EventListener;

use Loculus\SessionSecurityBundle\Event\InvalidSessionEvent;
use Loculus\SessionSecurityBundle\EventListener\InvalidSessionListener;
use Loculus\SessionSecurityBundle\InvalidationStrategyManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class InvalidSessionListenerTest extends TestCase
{
    private InvalidationStrategyManager|MockObject $invalidationStrategyManager;

    private InvalidSessionEvent|MockObject $event;
    private string $eventType = 'session_security.invalid_session';
    private EventDispatcherInterface|MockObject $eventDispatcher;

    protected function setUp(): void
    {
        $this->invalidationStrategyManager = $this->createMock(InvalidationStrategyManager::class);

        $this->event = $this->createMock(InvalidSessionEvent::class);
        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
    }

    public function testConstructingRequestListenerWithNoEnabledInvalidationStrategies(): void
    {
        $enabledStrategies = [];

        $invalidSessionListener = new InvalidSessionListener(
            $this->invalidationStrategyManager,
            $enabledStrategies,
        );

        $this->invalidationStrategyManager->expects(self::never())
            ->method('setup')
            ->with($enabledStrategies)
        ;
        $this->invalidationStrategyManager->expects(self::never())
            ->method('handle')
        ;

        $invalidSessionListener->__invoke(
            $this->event,
            $this->eventType,
            $this->eventDispatcher,
        );
    }

    public function testConstructingRequestListenerWithTwoEnabledInvalidationStrategies(): void
    {
        $enabledStrategies = [
            'session_regenerate_id_strategy',
            'throw_invalid_session_exception_strategy',
        ];

        $this->invalidationStrategyManager->expects(self::once())
            ->method('setup')
            ->with($enabledStrategies)
        ;
        $this->invalidationStrategyManager->expects(self::once())
            ->method('handle')
        ;

        $invalidSessionListener = new InvalidSessionListener(
            $this->invalidationStrategyManager,
            $enabledStrategies,
        );

        $invalidSessionListener->__invoke(
            $this->event,
            $this->eventType,
            $this->eventDispatcher,
        );
    }
}
