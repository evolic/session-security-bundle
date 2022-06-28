<?php
declare(strict_types=1);

namespace Loculus\SessionSecurityBundle;

use Loculus\SessionSecurityBundle\Event\InvalidSessionEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ValidationManager
{
    private ValidatorChain $validatorChain;
    private EventDispatcherInterface $eventDispatcher;
    private LoggerInterface $logger;

    public function __construct(
        ValidatorChain $validatorChain,
        EventDispatcherInterface $eventDispatcher,
        LoggerInterface $logger,
    ) {
        $this->validatorChain = $validatorChain;
        $this->eventDispatcher = $eventDispatcher;
        $this->logger = $logger;
    }

    public function setup(array $config, SessionInterface $session): void
    {
        $this->validatorChain->setEnabledValidators($config);

        $sessionKey = 'session_validators';

        if ($session->has($sessionKey)) {
            $data = $session->get($sessionKey);

            foreach ($this->validatorChain->getEnabledValidators() as $enabledValidator) {
                $enabledValidator->setData($data[$enabledValidator->getName()]);
            }
        } else {
            $data = [];

            foreach ($this->validatorChain->getEnabledValidators() as $enabledValidator) {
                $data[$enabledValidator->getName()] = $enabledValidator->getData();
            }

            $session->set($sessionKey, $data);
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
            $this->logger->error('Dispatching InvalidSessionEvent');

            $event = new InvalidSessionEvent($type);

            $this->eventDispatcher->dispatch($event);
        }
    }
}
