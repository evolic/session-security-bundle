<?php

/*
 * (c) Tomasz Kuter <tkuter@loculus.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\DependencyInjection\CompilerPass;

use Loculus\SessionSecurityBundle\ValidatorChain;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ValidatorChainCompilerPass implements CompilerPassInterface
{
    public const TAG = 'session_security.validator.validator_interface';

    public function process(ContainerBuilder $container): void
    {
        if ($container->has(ValidatorChain::class)) {
            $this->setupValidatorChain($container);
        }
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    private function setupValidatorChain(ContainerBuilder $container): void
    {
        $arguments = [];
        $definition = $container->findDefinition(ValidatorChain::class);
        $validators = $container->findTaggedServiceIds(self::TAG);

        foreach (array_keys($validators) as $id) {
            $arguments[] = new Reference($id);
        }

        $definition->setArguments($arguments);
    }
}
