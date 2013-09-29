<?php

namespace WMC\Symfony\AclBundle\Exception;

class InvalidAclTargetObjectException extends \RuntimeException
{
    protected $targetObject;

    public function setTargetObject($targetObject)
    {
        $this->targetObject = $targetObject;
    }

    public function getTargetObject()
    {
        return $this->targetObject;x
    }
}