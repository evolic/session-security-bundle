<?php
declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\InvalidationStrategy;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use function session_regenerate_id;

class RegenerateSessionIdStrategy implements InvalidationStrategyInterface
{
    private const NAME = 'session_regenerate_id';

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
        $this->logger->critical(self::NAME);
        $this->logger->critical(session_regenerate_id() ? 'regenerated' : 'not regenerated');
        $this->logger->critical(session_destroy() ? 'destroyed' : 'not destroyed');
    }
}
