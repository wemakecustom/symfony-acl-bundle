<?php

namespace WMC\Symfony\AclBundle\Provider\Doctrine\ORM;

use WMC\Symfony\AclBundle\Provider\Doctrine\SecurityIdentityFactory as BaseFactory;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Events;

class SecurityIdentityFactory extends BaseFactory
{
    public function __construct(ObjectManager $manager)
    {
        call_user_func_array(array($this, 'parent::__construct'), func_get_args());

        $manager->getEventManager()->addEventListener(array(Events::onClear), $this);
    }

    public function onClear()
    {
        $this->clearCache();
    }
}
