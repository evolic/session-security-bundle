<?php

/*
 * (c) Tomasz Kuter <tkuter@loculus.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\InvalidationStrategy;

use Psr\Log\LoggerInterface;

abstract class AbstractInvalidationStrategy
{
    protected const NAME = 'abstract_strategy';
    protected const INFO_MESSAGE = 'Applying "%s"';
    protected const EXCEPTION_MESSAGE = 'Session validation failed';

    public function __construct(
        protected LoggerInterface $logger,
    ) {
    }

    public function execute(): void
    {
        $this->logger->info(sprintf(static::INFO_MESSAGE, static::NAME));
    }

    public function getName(): string
    {
        return static::NAME;
    }
}
