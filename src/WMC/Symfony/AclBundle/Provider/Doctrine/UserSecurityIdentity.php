<?php

namespace WMC\Symfony\AclBundle\Provider\Doctrine;

/**
 * SecurityIdentity for a User
 */
class UserSecurityIdentity extends AbstractSecurityIdentity
{
    protected $kind = self::KIND_USER;

    public function __construct($className, $identifier)
    {
        parent::__construct(self::KIND_USER, $className, $identifier);
    }

    public function getKind()
    {
        return self::KIND_USER;
    }
}
