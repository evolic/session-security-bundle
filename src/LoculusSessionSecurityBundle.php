<?php

/*
 * (c) Tomasz Kuter <tkuter@loculus.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Loculus\SessionSecurityBundle;

use Loculus\SessionSecurityBundle\DependencyInjection\CompilerPass\InvalidationStrategyChainCompilerPass;
use Loculus\SessionSecurityBundle\DependencyInjection\CompilerPass\ValidatorChainCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class LoculusSessionSecurityBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new ValidatorChainCompilerPass());
        $container->addCompilerPass(new InvalidationStrategyChainCompilerPass());
    }
}
