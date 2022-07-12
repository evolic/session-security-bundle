<?php

/*
 * (c) Tomasz Kuter <tkuter@loculus.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\Event;

use Symfony\Contracts\EventDispatcher\Event;

class InvalidSessionEvent extends Event
{
    public const NAME = 'invalid_session';

    private string $invalidationType;

    public function __construct(
        string $invalidationType,
    ) {
        $this->invalidationType = $invalidationType;
    }

    public function getInvalidationType(): string
    {
        return $this->invalidationType;
    }
}
