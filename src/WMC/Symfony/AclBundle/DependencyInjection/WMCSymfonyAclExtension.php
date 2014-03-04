<?php

namespace WMC\Symfony\AclBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class WMCSymfonyAclExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration;
        $config = $this->processConfiguration($configuration, $configs);

        if (!$config['enabled']) {
            $container->setParameter('wmc.acl.provider', '');
            return;
        }

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $configuration = new Configuration;
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('wmc.acl.provider', $config['provider']);

        if ('custom' !== $config['provider']) {
            $loader->load($config['provider'].'.yml');
        }

        if (0 === strncmp('doctrine', $config['provider'], 8)) {
            $this->loadDoctrineProvider($config['doctrine'], $container);
        }

        $container->setAlias('wmc.acl.voter.strategy', $config['access_granting_strategy']);
    }

    protected function loadDoctrineProvider(array $config, ContainerBuilder $container)
    {
        if ($container->hasParameter('wmc.acl.provider.doctrine.registry_manager.service_name')) {
            $container->setAlias('wmc.acl.provider.doctrine.registry_manager', $container->getParameter('wmc.acl.provider.doctrine.registry_manager.service_name'));
        }

        if (isset($config['manager'])) {
            $container->setAlias('wmc.acl.provider.doctrine.object_manager', $config['manager']);
        }
    }
}
