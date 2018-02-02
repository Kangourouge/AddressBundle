<?php

namespace KRG\AddressBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('emc_address');

        $rootNode
            ->children()
                ->arrayNode('google_maps')
                    ->children()
                        ->scalarNode('api_key')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('countries')->defaultValue(null)->end()
                        ->booleanNode('ask_precise_coordinates')->defaultValue(true)->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
