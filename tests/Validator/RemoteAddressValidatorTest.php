<?php

/*
 * (c) Tomasz Kuter <tkuter@loculus.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\Tests\Validator;

use Loculus\SessionSecurityBundle\Validator\RemoteAddressValidator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class RemoteAddressValidatorTest extends TestCase
{
    private const IP_LOCALHOST = '127.0.0.1';
    private const IP_CLASS_C = '192.168.0.1';

    private RequestStack|MockObject $requestStack;
    private Request|MockObject $request;

    protected function setUp(): void
    {
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->request = $this->createMock(Request::class);
    }

    public function testConstructingValidator(): void
    {
        $this->requestStack->expects(self::once())
            ->method('getMainRequest')
            ->willReturn($this->request)
        ;

        $this->request->expects(self::once())
            ->method('getClientIp')
            ->willReturn(self::IP_LOCALHOST)
        ;

        $validator = new RemoteAddressValidator($this->requestStack);

        self::assertEquals(self::IP_LOCALHOST, $validator->getData());
    }

    public function testSettingTheSameDataAndValidatingRemoteAddress(): void
    {
        $this->requestStack->expects(self::exactly(2))
            ->method('getMainRequest')
            ->willReturn($this->request)
        ;

        $this->request->expects(self::exactly(2))
            ->method('getClientIp')
            ->willReturn(self::IP_LOCALHOST)
        ;

        $validator = new RemoteAddressValidator($this->requestStack);

        $validator->setData(self::IP_LOCALHOST);

        self::assertEquals(self::IP_LOCALHOST, $validator->getData());
        self::assertTrue($validator->isValid());
    }

    public function testSettingDifferentDataAndValidatingRemoteAddress(): void
    {
        $this->requestStack->expects(self::exactly(2))
            ->method('getMainRequest')
            ->willReturn($this->request)
        ;

        $this->request->expects(self::exactly(2))
            ->method('getClientIp')
            ->willReturn(self::IP_LOCALHOST)
        ;

        $validator = new RemoteAddressValidator($this->requestStack);

        $validator->setData(self::IP_CLASS_C);

        self::assertEquals(self::IP_CLASS_C, $validator->getData());
        self::assertFalse($validator->isValid());
    }

    public function testGettingNameOfTheValidator(): void
    {
        $this->requestStack->expects(self::once())
            ->method('getMainRequest')
            ->willReturn($this->request)
        ;

        $validator = new RemoteAddressValidator($this->requestStack);
        self::assertEquals('ip_address_validator', $validator->getName());
    }
}
