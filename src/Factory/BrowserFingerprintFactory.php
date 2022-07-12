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

class BrowserFingerprintFactory implements BrowserFingerprintFactoryInterface
{
    private BrowserFingerprint|bool $browserFingerprint = false;

    public function __construct(
        private GetBrowserFactoryInterface $getBrowserFactory,
    ) {
    }

    public function create(): ?BrowserFingerprint
    {
        if ($this->browserFingerprint !== false) {
            return $this->browserFingerprint;
        }

        $browser = $this->getBrowserFactory->get();

        if (empty($browser)) {
            return null;
        }

        $this->browserFingerprint = new BrowserFingerprint(
            $browser['browser'],
            $browser['version'],
            $browser['platform'],
            $browser['device_type'],
            (bool) $browser['ismobiledevice'],
            (bool) $browser['istablet'],
        );

        return $this->browserFingerprint;
    }
}
