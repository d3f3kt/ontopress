<?php

namespace OntoPress\Libary;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class RouterConfig implements ConfigurationInterface
{
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
