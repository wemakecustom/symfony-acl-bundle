<?php

namespace WMC\Symfony\AclBundle\Domain;

use WMC\Symfony\AclBundle\Model\AclSecurityIdentityFactoryInterface;

use WMC\Symfony\AclBundle\Exception\InvalidAclSecurityObjectException;

use WMC\Symfony\AclBundle\Model\AclSecurityIdentityInterface;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface as Token;
use Symfony\Component\Security\Core\Role\RoleInterface as Role;
use Symfony\Component\Security\Core\User\UserInterface As User;

class AclSecurityIdentityFactory implements AclSecurityIdentityFactoryInterface
{
    protected $anonymousIdentity;

    public function extractSecurityIdentity($grantee)
    {
        if ($grantee instanceof AclSecurityIdentity) {
            return $grantee;
        }

        if (null === $grantee) {
            return $this->createAnonymousIdentity();
        }

        if ($grantee instanceof Token) {
            return null === ($grantee = $grantee->getUser())
                ? $this->createAnonymousIdentity()
                : $this->createUserIdentity($grantee);
        }

        if ($grantee instanceof User) {
            return $this->createUserIdentity($grantee);
        }

        if (is_string($grantee) || $grantee instanceof Role) {
            return $this->createRoleIdentity($grantee);
        }

        $e = new InvalidAclSecurityObjectException('This security object is not supported by this AclSecurityIdentityFactory');
        $e->setSecurityObject($grantee);
        throw $e;
    }

    protected function extractRoleIdentityFields($role)
    {
        if ($role instanceof AclSecurityObject) {
            return array(get_class($role), $role->getSecurityObjectIdentifier());
        } elseif ($role instanceof Role) {
            if (!is_string($roleName = $role->getRole())) {
                $e = new InvalidAclSecurityObjectException(
                                                           'A role implementing RoleInterface must return a string for getRole. '.
                                                           'Otherwise, this role is too complex to be supported'.
                                                           'by this RoleSecurityIdentity'.
                                                           'and it must implement AclSecurityObjectInterface.'
                                                           );
                $e->setSecurityObject($user);
                throw $e;
            }
            
            return array(get_class($role), $roleName);
        }

        if (is_string($role)) {
            return array('Symfony\Component\Security\Core\Role\RoleInterface', $role);
        }

        $e = new InvalidAclSecurityObjectException(
                                                   'A role must'.
                                                   'implement AclSecurityObjectInterface, '.
                                                   'implement RoleInterface, '.
                                                   'or be a native string.'
                                                   );
        $e->setSecurityObject($user);
        throw $e;
    }

    protected function extractUserIdentityFields($user)
    {
        // Tokens are not required to return an instance of UserInterface
        if (is_object($user)) {
            if ($user instanceof AclSecurityObject) {
                return array(get_class($user), $user->getSecurityObjectIdentifier());
            }

            try {
                if ($user instanceof User) {
                    return array(
                                 get_class($user),
                                 method_exists($user, 'getId') ? $user->getId() : $user->getUsername()
                                 );
                } 

                if (method_exists($user, '__toString')) {
                    return array(get_class($user), ''.$user);
                }
            } catch (\Exception $thrown) {
                $e = new InvalidAclSecurityObjectException($thrown->getMessage(), 0, $thrown);
                $e->setSecurityObject($user);
                throw $e;
            }
        } elseif (is_string($user)) {
            return array('Symfony\Component\Security\Core\User\UserInterface', $user);
        }

        $e = new InvalidAclSecurityObjectException(
                                                   'A user, such as one returned by a Token, must '.
                                                   'implement AclSecurityObjectInterface, '.
                                                   'implement UserInterface, '.
                                                   'support __toString, '.
                                                   'or be a native string.'
                                                   );
        $e->setSecurityObject($user);
        throw $e;
    }

    public function createAnonymousIdentity()
    {
        if (null === $this->anonymousIdentity) {
            $this->anonymousIdentity = new AclSecurityIdentity(AclSecurityIdentityInterface::KIND_ANONYMOUS);
        }

        return $this->anonymousIdentity;
    }

    public function createRoleIdentity($role)
    {
        list($className, $objectId) = $this->extractRoleIdentityFields($role);
        return new AclSecurityIdentity(AclSecurityIdentityInterface::KIND_ROLE, $className, $objectId);
    }

    public function createUserIdentity($user)
    {
        list($className, $objectId) = $this->extractUserIdentityFields($role);
        return new AclSecurityIdentity(AclSecurityIdentityInterface::KIND_USER, $className, $objectId);
    }
}
