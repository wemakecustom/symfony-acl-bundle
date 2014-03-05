<?php

namespace WMC\Symfony\AclBundle\Provider\Doctrine;

use WMC\Symfony\AclBundle\Domain\AclSecurityIdentity;

abstract class AbstractSecurityIdentity extends AclSecurityIdentity
{
    protected $id;

    public function getId()
    {
        return $this->id;
    }
}