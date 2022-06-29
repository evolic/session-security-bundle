<?php
declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\Tests\EventListener;

use Generator;
use Loculus\SessionSecurityBundle\EventListener\RequestListener;
use Loculus\SessionSecurityBundle\ValidationManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class RequestListenerTest extends TestCase
{
    private ValidationManager|MockObject $validationManager;

    private RequestEvent|MockObject $requestEvent;
    private Request|MockObject $request;
    private SessionInterface|MockObject $session;
    private EventDispatcherInterface|MockObject $eventDispatcher;

    protected function setUp(): void
    {
        $this->validationManager = $this->createMock(ValidationManager::class);

        $this->requestEvent = $this->createMock(RequestEvent::class);
        $this->request = $this->createMock(Request::class);
        $this->session = $this->createMock(SessionInterface::class);
        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
    }

    public function provideDataForConstructingRequestListener(): Generator
    {
        yield [
            []
        ];

        yield [
            [
                'ip_address_validator',
                'user_agent_validator',
            ]
        ];
    }

    /**
     * @dataProvider provideDataForConstructingRequestListener
     */
    public function testConstructingRequestListenerForNoMainRequest(array $config): void
    {
        $requestListener = new RequestListener($this->validationManager, $config);

        $this->requestEvent->expects(self::once())
            ->method('isMainRequest')
            ->willReturn(false)
        ;

        $requestListener->__invoke(
            $this->requestEvent,
            'kernel.request',
            $this->eventDispatcher
        );
    }

    /**
     * @dataProvider provideDataForConstructingRequestListener
     */
    public function testConstructingRequestListenerForMainRequest(array $config): void
    {
        $requestListener = new RequestListener($this->validationManager, $config);

        $this->requestEvent->expects(self::once())
            ->method('isMainRequest')
            ->willReturn(true)
        ;

        $this->requestEvent->expects(self::once())
            ->method('getRequest')
            ->willReturn($this->request)
        ;

        $this->request->expects(self::once())
            ->method('getSession')
            ->willReturn($this->session)
        ;

        $this->validationManager->expects(self::once())
            ->method('setup')
            ->with($config, $this->session)
        ;

        $this->validationManager->expects(self::once())
            ->method('validate')
            ->with()
        ;

        $requestListener->__invoke(
            $this->requestEvent,
            'kernel.request',
            $this->eventDispatcher
        );
    }
}
