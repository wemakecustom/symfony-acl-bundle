<?php

namespace WMC\Symfony\AclBundle\Provider\Doctrine;

use WMC\Symfony\AclBundle\Domain\AclTargetIdentity;

class AbstractTargetIdentity extends AclTargetIdentity
{
    protected $id;

    public function getId()
    {
        return $this->id;
    }
}