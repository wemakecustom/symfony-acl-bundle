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

        if (is_array($target)) {
            if (count($target) != 2) {
                $e = new InvalidAclTargetObjectException('compound target objects require exactly 2 components (object and field or class and field).');
                $e->setTargetObject($grantee);
                throw $e;
            }

            if (is_string($target[0])) {
                return AclClassFieldTargetIdentity::getInstance($target[0], $target[1]);
            } else {
                return new AclObjectFieldTargetIdentity($target[0], $target[1]);
            }
        }

        if (is_string($target)) {
            return AclClassTargetIdentity::getInstance($target);
        } else {
            return new AclObjectTargetIdentity($target);
        }
    }
}