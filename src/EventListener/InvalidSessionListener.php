<?php
declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\EventListener;

use Loculus\SessionSecurityBundle\Event\InvalidSessionEvent;
use Loculus\SessionSecurityBundle\InvalidationStrategyManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class InvalidSessionListener
{
     public function __construct(
        private InvalidationStrategyManager $invalidationStrategyManager,
        private LoggerInterface $logger,
        private array $config,
    ) {
    }

    public function __invoke(
        InvalidSessionEvent $event,
        string $eventType,
        EventDispatcherInterface $eventDispatcher,
    ): void {
        $this->logger->error($eventType);

        if (empty($this->config)) {
            return;
        }

        $this->invalidationStrategyManager->setup($this->config);
        $this->invalidationStrategyManager->handle();
    }
}
