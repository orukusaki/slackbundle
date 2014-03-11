<?php

namespace Orukusaki\Bundle\SlackBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('orukusaki_slack');

        $rootNode
            ->children()
            ->scalarNode('api_key')->end()
            ->arrayNode('identity')
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('username')->defaultValue('OrukusakiBot')->end()
                    ->scalarNode('emoji')->defaultValue(':space_invader:')->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
