<?php

namespace WMC\Symfony\AclBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('acl');

        $rootNode->canBeEnabled()->children()
            ->scalarNode('provider')->defaultValue('doctrine.orm')->end()
            ->end()->end()
            ;

        $rootNode->append($this->getDoctrineSection());

        return $treeBuilder;
    }

    protected function getDoctrineSection()
    {
        $treeBuilder = new TreeBuilder;
        $node = $treeBuilder->root('doctrine');

        $node->addDefaultsIfNotSet();

        $node->children()
            ->scalarNode('manager')->defaultNull()->end()
            ;

        return $node;
    }
}
