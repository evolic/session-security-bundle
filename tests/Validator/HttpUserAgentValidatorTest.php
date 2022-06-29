<?php
declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\Tests\Validator;

use Loculus\SessionSecurityBundle\Validator\HttpUserAgentValidator;
use PHPUnit\Framework\TestCase;

class HttpUserAgentValidatorTest extends TestCase
{
    private const BROWSER_FIREFOX = 'Mozilla/5.0 (X11; Linux x86_64; rv:78.0) Gecko/20100101 Firefox/78.0';
    private const BROWSER_LYNX = 'Lynx/2.8.9rel.1 libwww-FM/2.14 SSL-MM/1.4.1 GNUTLS/3.6.5';

    public function testConstructingValidatorWithNoData(): void
    {
        $_SERVER['HTTP_USER_AGENT'] = self::BROWSER_FIREFOX;
        $data = null;
        $validator = new HttpUserAgentValidator($data);

        self::assertEquals(self::BROWSER_FIREFOX, $validator->getData());
    }

    public function testConstructingValidatorWithData(): void
    {
        $data = self::BROWSER_LYNX;
        $validator = new HttpUserAgentValidator($data);

        self::assertEquals(self::BROWSER_LYNX, $validator->getData());
    }

    public function testSettingTheSameDataAndValidatingBrowser(): void
    {
        $_SERVER['HTTP_USER_AGENT'] = self::BROWSER_FIREFOX;
        $data = null;
        $validator = new HttpUserAgentValidator($data);

        $validator->setData(self::BROWSER_FIREFOX);

        self::assertEquals(self::BROWSER_FIREFOX, $validator->getData());
        self::assertTrue($validator->isValid());
    }

    public function testSettingDifferentDataAndValidatingBrowser(): void
    {
        $_SERVER['HTTP_USER_AGENT'] = self::BROWSER_FIREFOX;
        $data = null;
        $validator = new HttpUserAgentValidator($data);

        $validator->setData(self::BROWSER_LYNX);

        self::assertEquals(self::BROWSER_LYNX, $validator->getData());
        self::assertFalse($validator->isValid());
    }

    public function testGettingNameOfTheValidator(): void
    {
        $validator = new HttpUserAgentValidator();
        self::assertEquals('user_agent_validator', $validator->getName());
    }
}
