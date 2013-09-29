<?php

namespace WMC\Symfony\AclBundle\Domain;

use WMC\Symfony\AclBundle\Model\AclSecurityIdentityInterface;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;

/**
 * SecurityIdentity for an AnonymousToken
 */
final class AnonymousSecurityIdentity implements AclSecurityIdentityInterface
{
    private static $instance;

    public static function getInstance()
    {
        return null === static::$instance
            ? static::$instance = new static
            : static::$instance;
    }

    private function __construct()
    {
    }

    public function getClassName()
    {
        return 'Symfony\Component\Security\Core\Authentication\Token\AnonymousToken';
    }

    public function getObjectIdentifier()
    {
        return null;
    }

    public function equals(AclSecurityIdentityInterface $identity)
    {
        return $identity instanceof static;
    }
}