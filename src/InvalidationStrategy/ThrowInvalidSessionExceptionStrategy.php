<?php
declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\InvalidationStrategy;

use Loculus\SessionSecurityBundle\Exception\InvalidSessionException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use function session_regenerate_id;

class ThrowInvalidSessionExceptionStrategy implements InvalidationStrategyInterface
{
    private const NAME = 'throw_invalid_session_exception_strategy';

    private LoggerInterface $logger;

    public function __construct(
        LoggerInterface $logger,
    ) {
        $this->logger = $logger;
    }

    public function getName(): string
    {
        return self::NAME;
    }
    
    public function execute(): void
    {
        throw new InvalidSessionException('Session validation failed');
    }
}
