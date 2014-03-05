<?php

namespace WMC\Symfony\AclBundle\Provider\Doctrine;

/**
 * SecurityIdentity for a Role
 */
class RoleSecurityIdentity extends AbstractSecurityIdentity
{
    protected $kind = self::KIND_ROLE;

    public function __construct($className, $identifier)
    {
        parent::__construct(self::KIND_ROLE, $className, $identifier);
    }

    public function getKind()
    {
        return self::KIND_ROLE;
    }
}
