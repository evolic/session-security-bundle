<?php

/*
 * (c) Tomasz Kuter <tkuter@loculus.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\Tests\Factory;

use Loculus\SessionSecurityBundle\BrowserFingerprint;
use Loculus\SessionSecurityBundle\Factory\BrowserFingerprintFactory;
use Loculus\SessionSecurityBundle\Factory\GetBrowserFactoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class BrowserFingerprintFactoryTest extends TestCase
{
    private GetBrowserFactoryInterface|MockObject $getBrowserFactory;

    private BrowserFingerprintFactory $browserFingerprintFactory;

    protected function setUp(): void
    {
        $this->getBrowserFactory = $this->createMock(GetBrowserFactoryInterface::class);

        $this->browserFingerprintFactory = new BrowserFingerprintFactory($this->getBrowserFactory);
    }

    public function testCreateMethodWithNoBrowserInformation(): void
    {
        $this->getBrowserFactory->expects(self::once())
            ->method('get')
            ->willReturn(false)
        ;

        self::assertNull($this->browserFingerprintFactory->create());
    }

    public function testCreateMethodWithBrowserInformation(): void
    {
        $browserData = [
            'platform' => 'Linux',
            'browser' => 'Firefox',
            'version' => '78.0',
            'device_type' => 'Desktop',
            'ismobiledevice' => '',
            'istablet' => '',
        ];

        $this->getBrowserFactory->expects(self::once())
            ->method('get')
            ->willReturn($browserData)
        ;

        $browserFingerprint = $this->browserFingerprintFactory->create();

        self::assertInstanceOf(BrowserFingerprint::class, $browserFingerprint);

        self::assertEquals($browserData['platform'], $browserFingerprint->getPlatform());
        self::assertEquals($browserData['browser'], $browserFingerprint->getBrowserName());
        self::assertEquals($browserData['version'], $browserFingerprint->getBrowserVersion());
        self::assertEquals($browserData['device_type'], $browserFingerprint->getDeviceType());
        self::assertEquals((bool) $browserData['ismobiledevice'], $browserFingerprint->isMobileDevice());
        self::assertEquals((bool) $browserData['istablet'], $browserFingerprint->isTablet());

        $browserFingerprint = $this->browserFingerprintFactory->create();

        self::assertInstanceOf(BrowserFingerprint::class, $browserFingerprint);
    }
}
