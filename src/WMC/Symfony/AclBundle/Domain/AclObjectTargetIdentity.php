<?php

namespace WMC\Symfony\AclBundle\Domain;

use WMC\Symfony\AclBundle\Model\AclTargetIdentityInterface;
use WMC\Symfony\AclBundle\Model\AclTargetObjectInterface;

use Symfony\Component\Security\Core\Util\ClassUtils;

use WMC\Symfony\AclBundle\Exception\InvalidAclTargetObjectException;

class AclObjectTargetIdentity implements AclTargetIdentityInterface
{
    protected $class_name;
    protected $object_id;

    public function __construct($object)
    {
        if (!is_object($object)) {
            $e = new InvalidAclTargetObjectException('ACLs can only be attached to objects');
            $e->setTargetObject($object);
            throw $e;
        }

        $this->class_name = ClassUtils::getRealClass(get_class($object));

        try {
            if ($object instanceof AclTargetObjectInterface) {
                $this->object_id = $object->getTargetObjectIdentifier();
            } elseif (method_exists($object, 'getId')) {
                $this->object_id = $object->getId();
            } else {
                $e = new InvalidAclTargetObjectException('ACLs can only be attached to an object if it either implements the AclSecurityObjectInterface, or has a method named "getId".');
                $e->setTargetObject($object);
                throw $e;
            }
        } catch (\InvalidArgumentException $invalid) {
            $e = new InvalidAclTargetObjectException($invalid->getMessage(), 0, $invalid);
            $e->setTargetObject($object);
            throw $e;
        }
    }

    public function getClassName()
    {
        return $this->class_name;
    }

    public function getObjectIdentifier()
    {
        return $this->object_id;
    }

    public function getFieldName()
    {
        return null;
    }

    public function equals(AclTargetIdentityInterface $identity)
    {
        return $identity instanceof AclTargetIdentityInterface
            && $identity->getClassName() === $identity->getClassName()
            && $identity->getObjectIdentifier() === $this->getObjectIdentifier()
            && $identity->getFieldName();
    }
}