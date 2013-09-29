<?php

namespace WMC\Symfony\AclBundle\Domain;

use WMC\Symfony\AclBundle\Model\AclTargetIdentityInterface;
use WMC\Symfony\AclBundle\Model\AclTargetObjectInterface;

use Symfony\Component\Security\Core\Util\ClassUtils;

use WMC\Symfony\AclBundle\Exception\InvalidAclTargetObjectException;

class AclObjectFieldTargetIdentity extends AclObjectTargetIdentity implements AclTargetIdentityInterface
{
    protected $field_name;

    public function __construct($object, $field_name)
    {
        parent::__construct($object);

        if (!is_string($field_name)) {
            $e = new InvalidAclTargetObjectException('Field name must be a string.');
            $e->setTargetObject(array($object, $field_name));
            throw $e;
        }

        $this->field_name = $field_name;
    }

    public function getFieldName()
    {
        return $this->field_name;
    }
}