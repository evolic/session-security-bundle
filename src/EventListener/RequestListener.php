<?php
declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\EventListener;

use Loculus\SessionSecurityBundle\ValidationManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class RequestListener
{
    private array $config;
    private ValidationManager $validationManager;

    public function __construct(
        array $config,
        ValidationManager $validationManager,
    ) {
        $this->config = $config;
        $this->validationManager = $validationManager;
    }

    public function __invoke(
        RequestEvent $event,
        string $eventType,
        EventDispatcherInterface $eventDispatcher
    ): void {
        $this->validationManager->setup([]);
        $this->validationManager->validate();
    }
}
