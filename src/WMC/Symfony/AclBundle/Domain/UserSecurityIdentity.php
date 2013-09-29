<?php

namespace WMC\Symfony\AclBundle\Domain;

use WMC\Symfony\AclBundle\Model\AclSecurityIdentityInterface;
use WMC\Symfony\AclBundle\Model\AclSecurityObjectInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use Symfony\Component\Security\Core\Util\ClassUtils;

use WMC\Symfony\AclBundle\Exception\InvalidAclSecurityObjectException;

/**
 * SecurityIdentity for a User
 */
class UserSecurityIdentity implements AclSecurityIdentityInterface
{
    protected $class_name;
    protected $object_id;

    public function __construct(UserInterface $user)
    {
        $this->class_name = ClassUtils::getRealClass(get_class($user));

        try {
            if ($user instanceof AclSecurityObjectInterface) {
                $this->object_id = $user->getSecurityObjectIdentifier();
            } elseif (method_exists($user, 'getId')) {
                $this->object_id = $user->getId();
            }
        } catch (\InvalidArgumentException $invalid) {
            $e = new InvalidAclSecurityObjectException($invalid->getMessage(), 0, $invalid);
            $e->setSecurityObject($user);
            throw $e;
        }

        $e = new InvalidAclSecurityObjectException('A User must either implement the AclSecurityObjectInterface, or have a method named "getId".');
        $e->setSecurityObject($user);
        throw $e;
    }

    public function getClassName()
    {
        return $this->class_name;
    }

    public function getObjectIdentifier()
    {
        return $this->object_id;
    }

    public function equals(AclSecurityIdentityInterface $identity)
    {
        return $identity instanceof UserSecurityIdentity
            && $identity->getClassName() == $identity->getClassName()
            && $identity->getObjectIdentifier() == $this->getObjectIdentifier();
    }
}