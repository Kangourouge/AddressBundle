<?php

namespace KRG\AddressBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('emc_address');

        $rootNode
            ->children()
                ->scalarNode('address_class')->end() // TODO: useless, remove it
                ->scalarNode('country_class')->end() // TODO: useless, remove it
                ->arrayNode('google_maps')
                    ->children()
                        ->scalarNode('api_key')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('country')->defaultValue(null)->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
