<?php

namespace WMC\Symfony\AclBundle\Domain;

use WMC\Symfony\AclBundle\Model\AclTargetIdentityFactoryInterface;

use WMC\Symfony\AclBundle\Model\AclTargetIdentityInterface;

class AclTargetIdentityFactory implements AclTargetIdentityFactoryInterface
{
    public function extractTargetIdentity($target)
    {
        if ($target instanceof AclTargetIdentityInterface) {
            return $target;
        }

        if (is_array($target)) {
            if (2 != count($target) || !is_string($target[1])) {
                $e = new InvalidAclTargetObjectException('Compound target objects require exactly 2 components ([Object, string (Field name)] or [string (Class name), string (Field name)]).');
                $e->setTargetObject($grantee);
                throw $e;
            }

            return is_string($target[0])
                ? $this->createClassFieldIdentity($target[0], $target[1])
                : $this->createObjectFieldIdentity($target[0], $target[1]);
        }

        return is_string($target)
            ? $this->createClassIdentity($target)
            : $this->createObjectIdentity($target);
    }

    protected function extractObjectIdentityFields($object)
    {
        if (is_array($object)
            && 2 === count($object)
            && isset($object[0]) && is_string($object[0])
            && isset($object[1]) && is_string($object[1])) {
            return $object;
        }

        if (!is_object($object)) {
            $e = new InvalidAclTargetObjectException('ACLs can only be attached to actual objects');
            $e->setTargetObject($object);
            throw $e;
        }

        try {
            if ($object instanceof AclTargetObjectInterface) {
                return array(get_class($object), $object->getTargetObjectIdentifier());
            }

            if (method_exists($object, 'getId')) {
                return array(get_class($object), $object->getId());
            }

            if (method_exists($object, '__toString')) {
                return array(get_class($object), ''.$object);
            }
        } catch (\Exception $thrown) {
            $e = new InvalidAclTargetObjectException($thrown->getMessage(), 0, $thrown);
            $e->setTargetObject($object);
            throw $e;
        }

        $e = new InvalidAclTargetObjectException(
                                                 'For ACLs to be attached to an object, it must '.
                                                 'implement AclSecurityObjectInterface, '
                                                 .'have a getId() method, '.
                                                 .'or have a __toString() method.'
                                                 );
        $e->setTargetObject($object);
        throw $e;
    }

    public function createClassIdentity($className)
    {
        return new AclTargetIdentity($className);
    }

    public function createClassFieldIdentity($className, $fieldName)
    {
        return new AclTargetIdentity($className, null, $fieldName);
    }

    public function createObjectIdentity($object)
    {
        list($className, $identifier) = $this->extractObjectIdentityFields($object);
        return new AclTargetIdentityFactoryInterface($className, $identifier);
    }

    public function createObjectFieldIdentity($object, $fieldName)
    {
        list($className, $identifier) = $this->extractObjectIdentityFields($object);
        return new AclTargetIdentityFactoryInterface($className, $identifier, $fieldName);
    }
}