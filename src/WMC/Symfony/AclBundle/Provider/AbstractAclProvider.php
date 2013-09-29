<?php

namespace WMC\Symfony\AclBundle\Provider;

use WMC\Symfony\AclBundle\Model\AclMutableProviderInterface;

use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Role\RoleInterface;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Implements TargetIdentity and SecurityExtraction extraction from domain
 * objects.
 */
abstract class AbstractAclProvider implements AclProviderInterface
{
    public function extractSecurityIdentity($grantee)
    {
        if ($grantee instanceof AclSecurityIdentityInterface) {
            return $grantee;
        }

        if (null === $grantee || $grantee instanceof AnonymousToken) {
            return AnonymousSecurityIdentity:getInstance();
        }

        if (is_string($grantee) || $grantee instanceof RoleInterface) {
            return RoleSecurityIdentity::getInstance($grantee);
        }

        if ($grantee instanceof UserInterface) {
            return new UserSecurityIdentity($grantee);
        }

        $e = new InvalidAclSecurityObjectException('This security object is not supported by this AclProvider');
        $e->setSecurityObject($grantee);
        throw $e;
    }

    public function extractTargetIdentity($target)
    {
        if ($target instanceof AclTargetObjectInterface) {
            return $target;
        }


    }
}