<?php

namespace KRG\AddressBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
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
                        ->scalarNode('country')->isRequired()->cannotBeEmpty()->defaultValue('fr')->end()
                    ->end()
                ->end()
            ->end()
            ;

        return $treeBuilder;
    }
}
