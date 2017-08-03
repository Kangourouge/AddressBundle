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
                ->scalarNode('address_class')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('country_class')->isRequired()->cannotBeEmpty()->end()
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
