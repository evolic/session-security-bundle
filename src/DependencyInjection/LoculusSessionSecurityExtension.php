<?php
declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\DependencyInjection;

use Loculus\SessionSecurityBundle\DependencyInjection\CompilerPass\ValidatorChainCompilerPass;
use Loculus\SessionSecurityBundle\EventListener\RequestListener;
use Loculus\SessionSecurityBundle\Validator\ValidatorInterface;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class LoculusSessionSecurityExtension extends Extension
{
    public function getConfiguration(array $config, ContainerBuilder $container): ?ConfigurationInterface
    {
        return new Configuration();
    }

    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );

        $container
            ->registerForAutoconfiguration(ValidatorInterface::class)
            ->addTag(ValidatorChainCompilerPass::TAG)
        ;

        $loader->load('services.yml');

        $configuration = new Configuration();

        $config = $this->processConfiguration($configuration, $configs);
    }
}
