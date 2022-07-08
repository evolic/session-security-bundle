<?php
declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\Factory;

use Loculus\SessionSecurityBundle\BrowserFingerprint;

class BrowserFingerprintFactory implements BrowserFingerprintFactoryInterface
{
    private BrowserFingerprint|bool $browserFingerprint = false;

    public function create(): ?BrowserFingerprint
    {
        if ($this->browserFingerprint !== false) {
            return $this->browserFingerprint;
        }

        $browser = get_browser(null, true);

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
