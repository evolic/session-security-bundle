<?php
declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\InvalidationStrategy;

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
