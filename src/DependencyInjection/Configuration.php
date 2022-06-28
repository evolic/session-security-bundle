<?php
declare(strict_types=1);

namespace Loculus\SessionSecurityBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    private array $validators = [];

    public function __construct()
    {
        $this->validators = [];
    }

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('session_security');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('session_invalidation_strategy')
                    ->defaultNull()
                    ->example('session_regenerate_id')
                ->end()
                ->arrayNode('session_validators')
                    ->isRequired()
                    ->example(['ip_address', 'user_agent'])
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
