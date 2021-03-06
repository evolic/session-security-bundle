<?php

/*
 * (c) Tomasz Kuter <tkuter@loculus.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\Tests\Validator;

use Loculus\SessionSecurityBundle\BrowserFingerprint;
use Loculus\SessionSecurityBundle\Factory\BrowserFingerprintFactoryInterface;
use Loculus\SessionSecurityBundle\Validator\BrowserPlatformValidator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class BrowserPlatformValidatorTest extends TestCase
{
    private const PLATFORM_LINUX = 'Linux';
    private const PLATFORM_ANDROID = 'Android';

    private BrowserFingerprintFactoryInterface|MockObject $browserFingerprintFactory;

    private BrowserFingerprint|MockObject $browserFingerprint;

    protected function setUp(): void
    {
        $this->browserFingerprintFactory = $this->createMock(BrowserFingerprintFactoryInterface::class);

        $this->browserFingerprint = $this->createMock(BrowserFingerprint::class);
    }

    public function testConstructingValidator(): void
    {
        $platform = self::PLATFORM_LINUX;

        $this->browserFingerprint->expects(self::once())
            ->method('getPlatform')
            ->willReturn($platform)
        ;

        $this->browserFingerprintFactory->expects(self::once())
            ->method('create')
            ->willReturn($this->browserFingerprint)
        ;

        $validator = new BrowserPlatformValidator($this->browserFingerprintFactory);

        self::assertEquals($platform, $validator->getData());
    }

    public function testSettingTheSameDataAndValidatingBrowserPlatform(): void
    {
        $platform = self::PLATFORM_LINUX;

        $this->browserFingerprint->expects(self::exactly(2))
            ->method('getPlatform')
            ->willReturn($platform)
        ;

        $this->browserFingerprintFactory->expects(self::exactly(2))
            ->method('create')
            ->willReturn($this->browserFingerprint)
        ;

        $validator = new BrowserPlatformValidator($this->browserFingerprintFactory);

        self::assertEquals($platform, $validator->getData());

        self::assertTrue($validator->isValid());
    }

    public function testSettingDifferentDataAndValidatingBrowserPlatform(): void
    {
        $platform = self::PLATFORM_ANDROID;

        $this->browserFingerprint->expects(self::exactly(2))
            ->method('getPlatform')
            ->willReturn($platform)
        ;

        $this->browserFingerprintFactory->expects(self::exactly(2))
            ->method('create')
            ->willReturn($this->browserFingerprint)
        ;

        $validator = new BrowserPlatformValidator($this->browserFingerprintFactory);

        $validator->setData(self::PLATFORM_LINUX);

        self::assertEquals(self::PLATFORM_LINUX, $validator->getData());
        self::assertFalse($validator->isValid());
        self::assertStringStartsWith('Expected browser platform is not equal to actual', $validator->getErrorMessage());
    }
}
