<?php

namespace WMC\Symfony\AclBundle\Domain;

use WMC\Symfony\AclBundle\Model\AclSecurityIdentityInterface;

use Symfony\Component\Security\Core\Util\ClassUtils;

class AclSecurityIdentity implements AclSecurityIdentityInterface
{
    protected $kind;
    protected $className;
    protected $objectId;

    public function __construct($kind, $className = null, $objectId = null)
    {
        $this->kind      = $kind;

        $this->className = null === $className ? null : ClassUtils::getRealClass($className);
        $this->objectId  = $objectId;
    }

    public function getKind()
    {
        return $this->kind;
    }

    public function getClassName()
    {
        return $this->className;
    }

    public function getObjectIdentifier()
    {
        return $this->objectId;
    }

    public function equals(AclSecurityIdentityInterface $identity)
    {
        return $identity->getKind() === $this->getKind()
            && $identity->getClassName() === $this->getClassName()
            && $identity->getObjectIdentifier() === $this->getObjectIdentifier();
    }
}