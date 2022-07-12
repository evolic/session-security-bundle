<?php

/*
 * (c) Tomasz Kuter <tkuter@loculus.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Loculus\SessionSecurityBundle;

class BrowserFingerprint
{
    public function __construct(
        private string $browserName,
        private string $browserVersion,
        private string $platform,
        private string $deviceType,
        private bool $isMobileDevice,
        private bool $isTablet,
    ) {
    }

    public function getBrowserName(): string
    {
        return $this->browserName;
    }

    public function getBrowserVersion(): string
    {
        return $this->browserVersion;
    }

    public function getPlatform(): string
    {
        return $this->platform;
    }

    public function getDeviceType(): string
    {
        return $this->deviceType;
    }

    public function isMobileDevice(): bool
    {
        return $this->isMobileDevice;
    }

    public function isTablet(): bool
    {
        return $this->isTablet;
    }
}
