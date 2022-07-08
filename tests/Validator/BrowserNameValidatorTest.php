<?php
declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\Tests\Validator;

use Loculus\SessionSecurityBundle\BrowserFingerprint;
use Loculus\SessionSecurityBundle\Factory\BrowserFingerprintFactoryInterface;
use Loculus\SessionSecurityBundle\Validator\BrowserNameValidator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class BrowserNameValidatorTest extends TestCase
{
    private const BROWSER_NAME_FIREFOX = 'Firefox';
    private const BROWSER_NAME_CHROME = 'Chrome';

    private BrowserFingerprintFactoryInterface|MockObject $browserFingerprintFactory;

    private BrowserFingerprint|MockObject $browserFingerprint;

    protected function setUp(): void
    {
        $this->browserFingerprintFactory = $this->createMock(BrowserFingerprintFactoryInterface::class);

        $this->browserFingerprint = $this->createMock(BrowserFingerprint::class);
    }

    public function testConstructingValidatorWithNoData(): void
    {
        $data = null;
        $browserName = self::BROWSER_NAME_FIREFOX;

        $this->browserFingerprint->expects(self::once())
            ->method('getBrowserName')
            ->willReturn($browserName)
        ;

        $this->browserFingerprintFactory->expects(self::once())
            ->method('create')
            ->willReturn($this->browserFingerprint)
        ;

        $validator = new BrowserNameValidator($data, $this->browserFingerprintFactory);

        self::assertEquals($browserName, $validator->getData());
    }

    public function testConstructingValidatorWithData(): void
    {
        $data = self::BROWSER_NAME_FIREFOX;

        $validator = new BrowserNameValidator($data, $this->browserFingerprintFactory);

        self::assertEquals($data, $validator->getData());
    }

    public function testSettingTheSameDataAndValidatingBrowserName(): void
    {
        $data = self::BROWSER_NAME_FIREFOX;
        $browserName = self::BROWSER_NAME_FIREFOX;

        $this->browserFingerprint->expects(self::once())
            ->method('getBrowserName')
            ->willReturn($browserName)
        ;

        $this->browserFingerprintFactory->expects(self::once())
            ->method('create')
            ->willReturn($this->browserFingerprint)
        ;

        $validator = new BrowserNameValidator($data, $this->browserFingerprintFactory);

        self::assertEquals($browserName, $validator->getData());

        self::assertTrue($validator->isValid());
    }

    public function testSettingDifferentDataAndValidatingBrowserName(): void
    {
        $browserName = self::BROWSER_NAME_CHROME;
        $data = null;

        $this->browserFingerprint->expects(self::exactly(2))
            ->method('getBrowserName')
            ->willReturn($browserName)
        ;

        $this->browserFingerprintFactory->expects(self::exactly(2))
            ->method('create')
            ->willReturn($this->browserFingerprint)
        ;

        $validator = new BrowserNameValidator($data, $this->browserFingerprintFactory);

        $validator->setData(self::BROWSER_NAME_FIREFOX);

        self::assertEquals(self::BROWSER_NAME_FIREFOX, $validator->getData());
        self::assertFalse($validator->isValid());
        self::assertStringStartsWith('Expected browser name is not equal to actual', $validator->getErrorMessage());
    }
}
