<?php
declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\InvalidationStrategy;

use Psr\Log\LoggerInterface;
use function session_regenerate_id;

class RegenerateSessionIdStrategy implements InvalidationStrategyInterface
{
    private const NAME = 'session_regenerate_id_strategy';

    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    public function getName(): string
    {
        return self::NAME;
    }
    
    public function execute(): void
    {
        $this->logger->critical(self::NAME);
        $this->logger->critical('session ' . (session_regenerate_id() ? 'regenerated' : 'not regenerated'));
        $this->logger->critical('session ' . (session_destroy() ? 'destroyed' : 'not destroyed'));
    }
}
