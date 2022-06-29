<?php
declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\EventListener;

use Loculus\SessionSecurityBundle\Event\InvalidSessionEvent;
use Loculus\SessionSecurityBundle\InvalidationStrategyChain;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class InvalidSessionListener
{
    private InvalidationStrategyChain $invalidationStrategyChain;
    private ?string $strategyName;
    private LoggerInterface $logger;

    public function __construct(
        InvalidationStrategyChain $invalidationStrategyChain,
        LoggerInterface $logger,
        ?string $strategyName = null,
    ) {
        $this->invalidationStrategyChain = $invalidationStrategyChain;
        $this->strategyName = $strategyName;
        $this->logger = $logger;
    }

    public function __invoke(
        InvalidSessionEvent $event,
        string $eventType,
        EventDispatcherInterface $eventDispatcher,
    ): void {
        $this->logger->error($eventType);

        if (null === $this->strategyName) {
            return;
        }

        $strategy = $this->invalidationStrategyChain->get($this->strategyName);

        $strategy->execute(/*$event->getSession()*/);
    }
}
