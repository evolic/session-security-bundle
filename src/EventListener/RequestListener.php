<?php

/*
 * (c) Tomasz Kuter <tkuter@loculus.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\EventListener;

use Loculus\SessionSecurityBundle\ValidationManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class RequestListener
{
    public function __construct(
        private ValidationManager $validationManager,
        private array $config = [],
    ) {
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
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
        $this->validationManager->validate();
    }
}
