<?php
declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\Factory;

interface GetBrowserFactoryInterface
{
    public function get(): array|false;
}
