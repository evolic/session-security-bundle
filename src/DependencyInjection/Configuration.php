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
                ->arrayNode('session_validators')
                    ->isRequired()
                    ->useAttributeAsKey('name')
                    ->prototype('variable')->end()
                    ->defaultValue([])
                    ->example(['ip_address_validator', 'user_agent_validator'])
                ->end()
                ->arrayNode('session_invalidation_strategies')
                    ->isRequired()
                    ->useAttributeAsKey('name')
                    ->prototype('variable')->end()
                    ->defaultValue([])
                    ->example(['session_regenerate_id_strategy', 'throw_invalid_session_exception_strategy'])
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
