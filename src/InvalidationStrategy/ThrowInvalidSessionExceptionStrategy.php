<?php

/*
 * (c) Tomasz Kuter <tkuter@loculus.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\InvalidationStrategy;

use Loculus\SessionSecurityBundle\Exception\InvalidSessionException;

/**
 * Throws InvalidSessionException, which forces application to display Error 500 page
 */
class ThrowInvalidSessionExceptionStrategy extends AbstractInvalidationStrategy implements InvalidationStrategyInterface
{
    protected const NAME = 'throw_invalid_session_exception_strategy';
    
    public function execute(): void
    {
        parent::execute();

        throw new InvalidSessionException(self::EXCEPTION_MESSAGE);
    }
}
