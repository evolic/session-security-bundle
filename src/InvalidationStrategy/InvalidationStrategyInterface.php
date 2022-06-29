<?php
declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\InvalidationStrategy;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

interface InvalidationStrategyInterface
{
    /**
     * Gets strategy name for use when session is invalid
     */
    public function getName(): string;

    /**
     * Executes code if session is invalid and specified strategy has been chosen
     */
    public function execute(): void;
}
