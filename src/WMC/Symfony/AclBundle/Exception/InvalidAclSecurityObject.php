<?php

namespace WMC\Symfony\AclBundle\Exception;

class InvalidAclSecurityObjectException extends \RuntimeException
{
    protected $securityObject;

    public function setSecurityObject($securityObject)
    {
        $this->securityObject = $securityObject;
    }

    public function getSecurityObject()
    {
        return $this->securityObject;x
    }
}