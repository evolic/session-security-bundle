<?php

/*
 * (c) Tomasz Kuter <tkuter@loculus.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Loculus\SessionSecurityBundle;

use Loculus\SessionSecurityBundle\Event\InvalidSessionEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ValidationManager
{
    public const SESSION_KEY = 'session_validators';

    private const ERROR_MESSAGE_WITH_USER_IDENTIFIER = 'Session validation failed for user "%s": %s';
    private const ERROR_MESSAGE_WITHOUT_USER_IDENTIFIER = 'Session validation failed: %s';

    public function __construct(
        private ValidatorChain $validatorChain,
        private EventDispatcherInterface $eventDispatcher,
        private LoggerInterface $logger,
        private TokenStorageInterface $tokenStorage,
    ) {
    }

    public function setup(
        array $config,
        SessionInterface $session,
    ): void {
        $this->validatorChain->setEnabledValidators($config);

        if ($session->has(self::SESSION_KEY)) {
            $data = $session->get(self::SESSION_KEY);

            foreach ($this->validatorChain->getEnabledValidators() as $enabledValidator) {
                $enabledValidator->setData($data[$enabledValidator->getName()] ?? null);
            }

            return;
        }

        $data = [];

        foreach ($this->validatorChain->getEnabledValidators() as $enabledValidator) {
            $data[$enabledValidator->getName()] = $enabledValidator->getData();
        }

        $session->set(self::SESSION_KEY, $data);
    }

    public function validate(): void
    {
        $valid = true;
        $type = null;
        $errorMessage = null;

        foreach ($this->validatorChain->getEnabledValidators() as $enabledValidator) {
            if (!$enabledValidator->isValid()) {
                $type = $enabledValidator->getName();
                $errorMessage = $enabledValidator->getErrorMessage();
                $valid = false;
                break;
            }
        }

        if ($valid) {
            return;
        }

        $this->dispatchInvalidSessionEvent($errorMessage, $type);
    }

    private function dispatchInvalidSessionEvent(string $errorMessage, string $type): void
    {
        $userIdentifier = $this->tokenStorage->getToken()?->getUserIdentifier();

        $this->logger->critical(
            (null === $userIdentifier)
            ?
            sprintf(self::ERROR_MESSAGE_WITHOUT_USER_IDENTIFIER, $errorMessage)
            :
            sprintf(self::ERROR_MESSAGE_WITH_USER_IDENTIFIER, $userIdentifier, $errorMessage)
        );

        $this->logger->debug('Dispatching InvalidSessionEvent: '.$type);

        $event = new InvalidSessionEvent($type);

        $this->eventDispatcher->dispatch($event, 'session_security.invalid_session');
    }
}
