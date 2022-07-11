<?php
declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\Tests\Validator;

use Loculus\SessionSecurityBundle\Validator\RemoteAddressValidator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class RemoteAddressValidatorTest extends TestCase
{
    private const IP_LOCALHOST = '127.0.0.1';
    private const IP_CLASS_C = '192.168.0.1';

    private Request|MockObject $request;

    protected function setUp(): void
    {
        $this->request = $this->createMock(Request::class);
    }

    public function testConstructingValidator(): void
    {
        $this->request->expects(self::once())
            ->method('getClientIp')
            ->willReturn(self::IP_LOCALHOST)
        ;

        $validator = new RemoteAddressValidator($this->request);

        self::assertEquals(self::IP_LOCALHOST, $validator->getData());
    }

    public function testSettingTheSameDataAndValidatingRemoteAddress(): void
    {
        $this->request->expects(self::exactly(2))
            ->method('getClientIp')
            ->willReturn(self::IP_LOCALHOST)
        ;

        $validator = new RemoteAddressValidator($this->request);

        $validator->setData(self::IP_LOCALHOST);

        self::assertEquals(self::IP_LOCALHOST, $validator->getData());
        self::assertTrue($validator->isValid());
    }

    public function testSettingDifferentDataAndValidatingRemoteAddress(): void
    {
        $this->request->expects(self::exactly(2))
            ->method('getClientIp')
            ->willReturn(self::IP_LOCALHOST)
        ;

        $validator = new RemoteAddressValidator($this->request);

        $validator->setData(self::IP_CLASS_C);

        self::assertEquals(self::IP_CLASS_C, $validator->getData());
        self::assertFalse($validator->isValid());
    }

    public function testGettingNameOfTheValidator(): void
    {
        $validator = new RemoteAddressValidator($this->request);
        self::assertEquals('ip_address_validator', $validator->getName());
    }
}
