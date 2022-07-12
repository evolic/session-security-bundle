<?php

/*
 * (c) Tomasz Kuter <tkuter@loculus.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
