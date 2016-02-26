<?php

namespace OntoPress\Libary;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * RouterConfig used by the Router Class and defines a configuration tree to handle the router configuration
 */
class RouterConfig implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('routing')
            ->children()
                ->arrayNode('sites')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('page_title')->end()
                            ->scalarNode('menu_title')->end()
                            ->scalarNode('capability')->end()
                            ->scalarNode('icon')->end()
                            ->integerNode('position')->end()
                            ->scalarNode('controller')->end()
                            ->arrayNode('sub_sites')
                                ->useAttributeAsKey('name')
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('page_title')->end()
                                        ->scalarNode('menu_title')->end()
                                        ->scalarNode('capability')->end()
                                        ->scalarNode('controller')->end()
                                        ->booleanNode('virtual')
                                            ->defaultFalse()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
