<?php
declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\EventListener;

use Loculus\SessionSecurityBundle\ValidationManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class RequestListener
{
    private ValidationManager $validationManager;
    private array $config;

    public function __construct(
        ValidationManager $validationManager,
        array $config = [],
    ) {
        $this->validationManager = $validationManager;
        $this->config = $config;
    }

    public function __invoke(
        RequestEvent $event,
        string $eventType,
        EventDispatcherInterface $eventDispatcher
    ): void {
        if (!$event->isMainRequest()) {
            return;
        }

        $session = $event->getRequest()->getSession();

        $this->validationManager->setup($this->config, $session);
        $this->validationManager->validate($event->getRequest()->getSession());
    }
}
