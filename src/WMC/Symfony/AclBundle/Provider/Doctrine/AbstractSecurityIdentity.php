<?php

namespace WMC\Symfony\AclBundle\Provider\Doctrine;

use WMC\Symfony\AclBundle\Domain\AbstractAclSecurityIdentity;

abstract class AbstractSecurityIdentity extends AbstractAclSecurityIdentity
{
    protected $id;

    public function getId()
    {
        return $this->id;
    }
}