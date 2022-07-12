<?php

/*
 * (c) Tomasz Kuter <tkuter@loculus.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\Factory;

use Loculus\SessionSecurityBundle\BrowserFingerprint;

interface BrowserFingerprintFactoryInterface
{
    public function create(): ?BrowserFingerprint;
}
