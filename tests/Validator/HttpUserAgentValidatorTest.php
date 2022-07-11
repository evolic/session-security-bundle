<?php
declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\Tests\Validator;

use Loculus\SessionSecurityBundle\Validator\HttpUserAgentValidator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request;

class HttpUserAgentValidatorTest extends TestCase
{
    private const BROWSER_FIREFOX = 'Mozilla/5.0 (X11; Linux x86_64; rv:78.0) Gecko/20100101 Firefox/78.0';
    private const BROWSER_LYNX = 'Lynx/2.8.9rel.1 libwww-FM/2.14 SSL-MM/1.4.1 GNUTLS/3.6.5';

    private Request|MockObject $request;
    private HeaderBag|MockObject $headers;

    protected function setUp(): void
    {
        $this->request = $this->getMockBuilder(Request::class)
            ->setConstructorArgs([
                [],
                [],
                [],
                [],
                [],
                [
                    'HTTP_USER_AGENT' => self::BROWSER_FIREFOX,
                ],
                [],
            ])
            ->getMock()
        ;

        $this->headers = $this->createMock(HeaderBag::class);

        $this->request->headers = $this->headers;
    }

    public function testConstructingValidator(): void
    {

        $this->headers->expects(self::once())
            ->method('get')
            ->with('User-Agent')
            ->willReturn(self::BROWSER_FIREFOX)
        ;

        $validator = new HttpUserAgentValidator($this->request);

        self::assertEquals(self::BROWSER_FIREFOX, $validator->getData());
    }

    public function testSettingTheSameDataAndValidatingBrowser(): void
    {
        $this->headers->expects(self::exactly(2))
            ->method('get')
            ->with('User-Agent')
            ->willReturn(self::BROWSER_FIREFOX)
        ;

        $validator = new HttpUserAgentValidator($this->request);

        $validator->setData(self::BROWSER_FIREFOX);

        self::assertEquals(self::BROWSER_FIREFOX, $validator->getData());
        self::assertTrue($validator->isValid());
    }

    public function testSettingDifferentDataAndValidatingBrowser(): void
    {
        $this->headers->expects(self::exactly(2))
            ->method('get')
            ->with('User-Agent')
            ->willReturn(self::BROWSER_FIREFOX)
        ;

        $validator = new HttpUserAgentValidator($this->request);

        $validator->setData(self::BROWSER_LYNX);

        self::assertEquals(self::BROWSER_LYNX, $validator->getData());
        self::assertFalse($validator->isValid());
    }

    public function testGettingNameOfTheValidator(): void
    {
        $validator = new HttpUserAgentValidator($this->request);
        self::assertEquals('user_agent_validator', $validator->getName());
    }
}
