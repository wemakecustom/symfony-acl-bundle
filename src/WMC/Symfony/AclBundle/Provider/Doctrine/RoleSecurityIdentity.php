<?php

namespace WMC\Symfony\AclBundle\Provider\Doctrine;

/**
 * SecurityIdentity for a Role
 */
class RoleSecurityIdentity extends AbstractSecurityIdentity
{
    public function __construct($className, $identifier)
    {
        parent::__construct($className, $identifier);
    }

    public function getKind()
    {
        return self::KIND_ROLE;
    }
}
