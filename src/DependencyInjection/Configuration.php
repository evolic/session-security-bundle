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
        $this->validators = ['ip_address', 'user_agent'];
    }

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('session_security');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('session_fixation_strategy')
                    ->defaultNull()
                    ->example('session_regenerate_id')
                ->end()
                ->enumNode('session_fixation_strategy')
                    ->values($this->validators)
                    ->defaultValue(null)
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
