<?php

/*
 * (c) Tomasz Kuter <tkuter@loculus.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\DependencyInjection;

use Loculus\SessionSecurityBundle\DependencyInjection\CompilerPass\InvalidationStrategyChainCompilerPass;
use Loculus\SessionSecurityBundle\DependencyInjection\CompilerPass\ValidatorChainCompilerPass;
use Loculus\SessionSecurityBundle\EventListener\InvalidSessionListener;
use Loculus\SessionSecurityBundle\EventListener\RequestListener;
use Loculus\SessionSecurityBundle\InvalidationStrategy\InvalidationStrategyInterface;
use Loculus\SessionSecurityBundle\Validator\ValidatorInterface;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class LoculusSessionSecurityExtension extends Extension
{
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getConfiguration(array $config, ContainerBuilder $container): ?ConfigurationInterface
    {
        return new Configuration();
    }

    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );

        $container
            ->registerForAutoconfiguration(ValidatorInterface::class)
            ->addTag(ValidatorChainCompilerPass::TAG)
        ;

        $container
            ->registerForAutoconfiguration(InvalidationStrategyInterface::class)
            ->addTag(InvalidationStrategyChainCompilerPass::TAG)
        ;

        $loader->load('services.yml');

        $configuration = new Configuration();

        $config = $this->processConfiguration($configuration, $configs);

        $definition = $container->getDefinition(RequestListener::class);
        $definition->replaceArgument('$config', $config['session_validators']);

        $definition = $container->getDefinition(InvalidSessionListener::class);
        $definition->replaceArgument('$config', $config['session_invalidation_strategies']);
    }
}
