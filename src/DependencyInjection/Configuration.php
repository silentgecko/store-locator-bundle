<?php

namespace Silentgecko\StoreLocatorBundle\DependencyInjection;

use Silentgecko\StoreLocator\DistanceCalculator\DistanceCalculatorGeotools;
use Silentgecko\StoreLocator\StoreList\InMemoryStoreListProvider;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('mablae_store_locator');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('repository_service')
                    ->defaultValue(InMemoryStoreListProvider::class)
                ->end()
                ->scalarNode('distance_calculator')
                    ->defaultValue(DistanceCalculatorGeotools::class)
                ->end()
            ->end();
        return $treeBuilder;
    }
}
