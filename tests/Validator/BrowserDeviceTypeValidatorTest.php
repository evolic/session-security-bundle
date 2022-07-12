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
use Loculus\SessionSecurityBundle\Validator\BrowserDeviceTypeValidator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class BrowserDeviceTypeValidatorTest extends TestCase
{
    private const DEVICE_TYPE_DESKTOP = 'Desktop';
    private const DEVICE_TYPE_MOBILE_PHONE = 'Mobile Phone';

    private BrowserFingerprintFactoryInterface|MockObject $browserFingerprintFactory;

    private BrowserFingerprint|MockObject $browserFingerprint;

    protected function setUp(): void
    {
        $this->browserFingerprintFactory = $this->createMock(BrowserFingerprintFactoryInterface::class);

        $this->browserFingerprint = $this->createMock(BrowserFingerprint::class);
    }

    public function testConstructingValidator(): void
    {
        $deviceType = self::DEVICE_TYPE_DESKTOP;

        $this->browserFingerprint->expects(self::once())
            ->method('getDeviceType')
            ->willReturn($deviceType)
        ;

        $this->browserFingerprintFactory->expects(self::once())
            ->method('create')
            ->willReturn($this->browserFingerprint)
        ;

        $validator = new BrowserDeviceTypeValidator($this->browserFingerprintFactory);

        self::assertEquals($deviceType, $validator->getData());
    }

    public function testSettingTheSameDataAndValidatingBrowserDeviceType(): void
    {
        $deviceType = self::DEVICE_TYPE_DESKTOP;

        $this->browserFingerprint->expects(self::exactly(2))
            ->method('getDeviceType')
            ->willReturn($deviceType)
        ;

        $this->browserFingerprintFactory->expects(self::exactly(2))
            ->method('create')
            ->willReturn($this->browserFingerprint)
        ;

        $validator = new BrowserDeviceTypeValidator($this->browserFingerprintFactory);

        self::assertEquals($deviceType, $validator->getData());

        self::assertTrue($validator->isValid());
    }

    public function testSettingDifferentDataAndValidatingBrowserDeviceType(): void
    {
        $deviceType = self::DEVICE_TYPE_MOBILE_PHONE;

        $this->browserFingerprint->expects(self::exactly(2))
            ->method('getDeviceType')
            ->willReturn($deviceType)
        ;

        $this->browserFingerprintFactory->expects(self::exactly(2))
            ->method('create')
            ->willReturn($this->browserFingerprint)
        ;

        $validator = new BrowserDeviceTypeValidator($this->browserFingerprintFactory);

        $validator->setData(self::DEVICE_TYPE_DESKTOP);

        self::assertEquals(self::DEVICE_TYPE_DESKTOP, $validator->getData());
        self::assertFalse($validator->isValid());

        self::assertStringStartsWith(
            'Expected browser device type is not equal to actual',
            $validator->getErrorMessage()
        );
    }
}
