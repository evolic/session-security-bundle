<?php
declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\Factory;

class GetBrowserFactory implements GetBrowserFactoryInterface
{
    public function get(): array|false
    {
        if (PHP_SAPI === 'cli') {
            return false;
        }

        return get_browser(null, true);
    }
}
