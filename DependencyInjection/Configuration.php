<?php

namespace OpenClassrooms\Bundle\ServiceProxyBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('openclassrooms_service_proxy');
        $rootNode->children()
            ->scalarNode('cache_dir')->cannotBeEmpty()->defaultValue('%kernel.cache_dir%/openclassrooms_service_proxy')
            ->end()
            ->scalarNode('default_cache')->defaultNull()->end()
            ->arrayNode('production_environments')->prototype('scalar')->end()->defaultValue(
                ['prod']
            )->end()
            ->end();

        return $treeBuilder;
    }
}
