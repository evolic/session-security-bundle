<?php
declare(strict_types=1);

namespace Loculus\SessionSecurityBundle;

use Loculus\SessionSecurityBundle\Event\InvalidSessionEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ValidationManager
{
    public const SESSION_KEY = 'session_validators';

    public function __construct(
        private ValidatorChain $validatorChain,
        private EventDispatcherInterface $eventDispatcher,
        private LoggerInterface $logger,
    ) {
    }

    public function setup(array $config, SessionInterface $session): void
    {
        $this->validatorChain->setEnabledValidators($config);

        if ($session->has(self::SESSION_KEY)) {
            $data = $session->get(self::SESSION_KEY);

            foreach ($this->validatorChain->getEnabledValidators() as $enabledValidator) {
                $enabledValidator->setData($data[$enabledValidator->getName()]);
            }
        } else {
            $data = [];

            foreach ($this->validatorChain->getEnabledValidators() as $enabledValidator) {
                $data[$enabledValidator->getName()] = $enabledValidator->getData();
            }

            $session->set(self::SESSION_KEY, $data);
        }
    }

    public function validate(): void
    {
        $valid = true;
        $type = null;

        foreach ($this->validatorChain->getEnabledValidators() as $enabledValidator) {
            if (!$enabledValidator->isValid()) {
                $type = $enabledValidator->getName();
                $valid = false;
                break;
            }
        }

        if (!$valid) {
            $this->logger->error('Dispatching InvalidSessionEvent: ' . $type);

            $event = new InvalidSessionEvent($type);

            $this->eventDispatcher->dispatch($event, 'session_security.invalid_session');
        }
    }
}
