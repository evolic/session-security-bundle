<?php

/*
 * (c) Tomasz Kuter <tkuter@loculus.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
