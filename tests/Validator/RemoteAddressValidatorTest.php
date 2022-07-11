<?php
declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\Tests\Validator;

use Loculus\SessionSecurityBundle\Validator\RemoteAddressValidator;
use PHPUnit\Framework\TestCase;

class RemoteAddressValidatorTest extends TestCase
{
    private const IP_LOCALHOST = '127.0.0.1';
    private const IP_CLASS_C = '192.168.0.1';

    public function testConstructingValidator(): void
    {
        $_SERVER['REMOTE_ADDR'] = self::IP_LOCALHOST;
        $validator = new RemoteAddressValidator();

        self::assertEquals(self::IP_LOCALHOST, $validator->getData());
    }

    public function testSettingTheSameDataAndValidatingRemoteAddress(): void
    {
        $_SERVER['REMOTE_ADDR'] = self::IP_LOCALHOST;
        $validator = new RemoteAddressValidator();

        $validator->setData(self::IP_LOCALHOST);

        self::assertEquals(self::IP_LOCALHOST, $validator->getData());
        self::assertTrue($validator->isValid());
    }

    public function testSettingDifferentDataAndValidatingRemoteAddress(): void
    {
        $_SERVER['REMOTE_ADDR'] = self::IP_LOCALHOST;
        $validator = new RemoteAddressValidator();

        $validator->setData(self::IP_CLASS_C);

        self::assertEquals(self::IP_CLASS_C, $validator->getData());
        self::assertFalse($validator->isValid());
    }

    public function testGettingNameOfTheValidator(): void
    {
        $validator = new RemoteAddressValidator();
        self::assertEquals('ip_address_validator', $validator->getName());
    }
}
