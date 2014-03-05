<?php

namespace WMC\Symfony\AclBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use WMC\Symfony\AclBundle\DependencyInjection\Compiler\RegisterMappingsPass;

class WMCSymfonyAclBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $mappings = array(
                          realpath(__DIR__ . '/Resources/config/doctrine') => 'WMC\Symfony\AclBundle\Provider\Doctrine',
                          );

        $container->addCompilerPass(RegisterMappingsPass::createOrmMappingDriver($mappings));
    }
}
