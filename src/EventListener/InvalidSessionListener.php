<?php
declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\EventListener;

use Loculus\SessionSecurityBundle\Event\InvalidSessionEvent;
use Loculus\SessionSecurityBundle\InvalidationStrategyManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class InvalidSessionListener
{
     public function __construct(
        private InvalidationStrategyManager $invalidationStrategyManager,
        private array $config,
    ) {
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function __invoke(
        InvalidSessionEvent $event,
        string $eventType,
        EventDispatcherInterface $eventDispatcher,
    ): void {
        if (empty($this->config)) {
            return;
        }

        $this->invalidationStrategyManager->setup($this->config);
        $this->invalidationStrategyManager->handle();
    }
}
