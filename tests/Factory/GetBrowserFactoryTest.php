<?php
declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\Tests\Factory;

use Loculus\SessionSecurityBundle\Factory\GetBrowserFactory;
use PHPUnit\Framework\TestCase;

class GetBrowserFactoryTest extends TestCase
{
    private GetBrowserFactory $getBrowserFactory;

    protected function setUp(): void
    {
        $this->getBrowserFactory = new GetBrowserFactory();
    }

    public function testGettingNoBrowserInformation(): void
    {
        self::assertFalse($this->getBrowserFactory->get());
    }
}
