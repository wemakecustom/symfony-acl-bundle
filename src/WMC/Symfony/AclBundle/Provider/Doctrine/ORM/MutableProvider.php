<?php

namespace WMC\Symfony\AclBundle\Provider\Doctrine\ORM;

use WMC\Symfony\AclBundle\Provider\Doctrine\MutableProvider as DoctrineProvider;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Events;

use WMC\Symfony\AclBundle\Model\AclTargetIdentityFactoryInterface as TargetIdentityFactory;
use WMC\Symfony\AclBundle\Model\AclSecurityIdentityFactoryInterface as SecurityIdentityFactory;

class MutableProvider extends DoctrineProvider
{
    public function __construct(SecurityIdentityFactory $securityIdentityFactory, TargetIdentityFactory $targetIdentityFactory,
                                ObjectManager $manager)
    {
        call_user_func_array(array($this, 'parent::__construct'), func_get_args());

        $manager->getEventManager()->addEventListener(array(Events::postFlush, Events::onClear), $this);
    }

    public function postFlush()
    {
        $this->clearCache();
    }

    public function onClear()
    {
        $this->clearCache();
    }
}
