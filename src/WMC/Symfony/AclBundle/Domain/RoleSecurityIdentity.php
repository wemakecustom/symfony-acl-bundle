<?php

namespace WMC\Symfony\AclBundle\Domain;

use WMC\Symfony\AclBundle\Model\AclSecurityIdentityInterface;
use WMC\Symfony\AclBundle\Model\AclSecurityObjectInterface;
use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * SecurityIdentity for a Role
 */
class RoleSecurityIdentity implements AclSecurityIdentityInterface
{
    protected static $instances = array();

    /**
     * Expects a role name or a Role Interface
     */
    public static function getInstance($role)
    {
        $role_name = $role;

        if ($role instanceof AclSecurityObjectInterface) {
            $role_name = $role->getSecurityObjectIdentifier();
        } elseif ($role instanceof RoleInterface) {
            $role_name = $role->getRole();
        }

        if (!is_string($role_name)) {
            return null;
        }

        return !isset(static::$instances[$role_name])
            ? static::$instances[$role_name] = new static($role_name)
            : static::$instances[$role_name];
    }

    protected $role_name;

    protected function __construct($role_name)
    {
        $this->role_name = $role_name;
    }

    public function getClassName()
    {
        return 'Symfony\Component\Security\Core\Role\RoleInterface';
    }

    public function getObjectIdentifier()
    {
        return $this->role_name;
    }

    public function equals(AclSecurityIdentityInterface $identity)
    {
        return $identity instanceof RoleSecurityIdentity && $identity->getObjectIdentifier() == $this->getObjectIdentifier();
    }
}