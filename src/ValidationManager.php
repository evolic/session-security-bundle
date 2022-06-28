<?php
declare(strict_types=1);

namespace Loculus\SessionSecurityBundle;

use Loculus\SessionSecurityBundle\Event\InvalidSessionEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

class ValidationManager
{
    private ValidatorChain $validatorChain;
    private EventDispatcher $eventDispatcher;

    public function __construct(
        ValidatorChain $validatorChain,
        EventDispatcher $eventDispatcher,
    ) {
        $this->validatorChain = $validatorChain;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function setup(array $config): void
    {
        $this->validatorChain->setEnabledValidators($config);
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
            $event = new InvalidSessionEvent($type);

            $this->eventDispatcher->dispatch($event, InvalidSessionEvent::NAME);
        }
    }
}
