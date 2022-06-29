<?php
declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\Event;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Contracts\EventDispatcher\Event;

class InvalidSessionEvent extends Event
{
    public const NAME = 'invalid_session';

    private string $invalidationType;
    private SessionInterface $session;

    public function __construct(
        string $invalidationType,
        SessionInterface $session
    ) {
        $this->invalidationType = $invalidationType;
    }

    public function getInvalidationType(): string
    {
        return $this->invalidationType;
    }

    public function getSession(): SessionInterface
    {
        return $this->session;
    }
}
