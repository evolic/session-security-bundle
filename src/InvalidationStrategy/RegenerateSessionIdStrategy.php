<?php
declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\InvalidationStrategy;

use function session_destroy;
use function session_regenerate_id;

/**
 * Regenerates session id and destroys invalid session
 */
class RegenerateSessionIdStrategy extends AbstractInvalidationStrategy implements InvalidationStrategyInterface
{
    protected const NAME = 'session_regenerate_id_strategy';

    public function execute(): void
    {
        parent::execute();

        if (PHP_SAPI !== 'cli') {
            $this->logger->critical('session ' . (session_regenerate_id() ? 'regenerated' : 'not regenerated'));
            $this->logger->critical('session ' . (session_destroy() ? 'destroyed' : 'not destroyed'));
        }
    }
}
