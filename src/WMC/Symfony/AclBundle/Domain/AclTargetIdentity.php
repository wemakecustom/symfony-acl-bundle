<?php

namespace WMC\Symfony\AclBundle\Domain;

use WMC\Symfony\AclBundle\Model\AclTargetIdentityInterface;

use Symfony\Component\Security\Core\Util\ClassUtils;

class AclTargetIdentity implements AclTargetIdentityInterface
{
    protected $className;
    protected $objectId;
    protected $fieldName;

    protected function __construct($className, $objectId = null, $fieldName = null)
    {
        $this->className = ClassUtils::getRealClass($className);
        $this->objectId  = $objectId;
        $this->fieldName = $fieldName;
    }

    public function getClassName()
    {
        return $this->className;
    }

    public function getObjectIdentifier()
    {
        return $this->objectId;
    }

    public function getFieldName()
    {
        return $this->fieldName;
    }

    public function equals(AclTargetIdentityInterface $identity)
    {
        return $identity->getClassName() === $this->getClassName()
            && $identity->getObjectIdentifier() === $this->getObjectIdentifier()
            && $identity->getFieldName() === $this->getFieldName();
    }
}