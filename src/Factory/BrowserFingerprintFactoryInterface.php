<?php
declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\Factory;

use Loculus\SessionSecurityBundle\BrowserFingerprint;

interface BrowserFingerprintFactoryInterface
{
    public function create(): ?BrowserFingerprint;
}
