<?php
declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\Tests\EventListener;

use Loculus\SessionSecurityBundle\Event\InvalidSessionEvent;
use Loculus\SessionSecurityBundle\EventListener\InvalidSessionListener;
use Loculus\SessionSecurityBundle\InvalidationStrategyManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class InvalidSessionListenerTest extends TestCase
{
    private InvalidationStrategyManager|MockObject $invalidationStrategyManager;
    private LoggerInterface|MockObject $logger;

    private InvalidSessionEvent|MockObject $event;
    private string $eventType = 'session_security.invalid_session';
    private EventDispatcherInterface|MockObject $eventDispatcher;

    protected function setUp(): void
    {
        $this->invalidationStrategyManager = $this->createMock(InvalidationStrategyManager::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->event = $this->createMock(InvalidSessionEvent::class);
        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
    }

    public function testConstructingRequestListenerWithNoEnabledInvalidationStrategies(): void
    {
        $enabledStrategies = [];

        $this->logger->expects(self::once())
            ->method('error')
            ->with($this->eventType)
        ;

        $invalidSessionListener = new InvalidSessionListener(
            $this->invalidationStrategyManager,
            $this->logger,
            $enabledStrategies,
        );

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

        $this->logger->expects(self::once())
            ->method('error')
            ->with($this->eventType)
        ;

        $this->invalidationStrategyManager->expects(self::once())
            ->method('setup')
            ->with($enabledStrategies)
        ;
        $this->invalidationStrategyManager->expects(self::once())
            ->method('handle')
        ;

        $invalidSessionListener = new InvalidSessionListener(
            $this->invalidationStrategyManager,
            $this->logger,
            $enabledStrategies,
        );

        $invalidSessionListener->__invoke(
            $this->event,
            $this->eventType,
            $this->eventDispatcher,
        );
    }
}
