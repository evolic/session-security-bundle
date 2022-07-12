<?php

/*
 * (c) Tomasz Kuter <tkuter@loculus.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\InvalidationStrategy;

use Symfony\Component\Security\Core\Exception\CookieTheftException;

/**
 * Throws CookieTheftException, which forces application to redirect to login page.
 * This works well if invalid session is destroyed.
 */
class ThrowCookieTheftExceptionStrategy extends AbstractInvalidationStrategy implements InvalidationStrategyInterface
{
    protected const NAME = 'throw_cookie_theft_exception_strategy';

    public function execute(): void
    {
        parent::execute();

        throw new CookieTheftException(self::EXCEPTION_MESSAGE);
    }
}
