<?php
declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\DependencyInjection\CompilerPass;

use Loculus\SessionSecurityBundle\InvalidationStrategyChain;
use Loculus\SessionSecurityBundle\ValidatorChain;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class InvalidationStrategyChainCompilerPass implements CompilerPassInterface
{
    public const TAG = 'session_security.session_invalidation_strategy.strategy_interface';

    public function process(ContainerBuilder $container): void
    {
        if ($container->has(InvalidationStrategyChain::class)) {
            $this->setupInvalidationStrategyChain($container);
        }
    }

    private function setupInvalidationStrategyChain(ContainerBuilder $container): void
    {
        $arguments = [];
        $definition = $container->findDefinition(InvalidationStrategyChain::class);
        $strategies = $container->findTaggedServiceIds(self::TAG);

        foreach ($strategies as $id => $strategy) {
            $arguments[] = new Reference($id);
        }

        $definition->setArguments($arguments);
    }
}
