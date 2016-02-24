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
                    ->children()
                        ->scalarNode('page_title')->end()
                        ->scalarNode('menu_title')->end()
                        ->scalarNode('capability')->end()
                        ->scalarNode('slug')->end()
                        ->scalarNode('icon')->end()
                        ->integerNode('position')->end()
                        ->arrayNode('sub_sites')
                            ->children()
                                ->scalarNode('parent')->end()
                                ->scalarNode('page_title')->end()
                                ->scalarNode('menu_title')->end()
                                ->scalarNode('capability')->end()
                                ->scalarNode('slug')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
        return $treeBuilder;
    }
}
