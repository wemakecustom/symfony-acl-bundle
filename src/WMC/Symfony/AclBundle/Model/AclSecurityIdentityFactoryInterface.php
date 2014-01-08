<?php

namespace WMC\Symfony\AclBundle\Model;

interface AclSecurityIdentityFactoryInterface
{
    /**
     * Convert a Security object (potential grantee) to a
     * AclSecurityIdentityInterface
     *
     * Accepts (not processed in this order):
     *  - null (Anonymous identity)
     *  - string (Role name)
     *  - Role instance
     *  - Token instance (incl. anonymous)
     *  - User instance
     *  - AclSecurityIdentityInterface (returns itself)
     */
    public function extractSecurityIdentity($grantee);

    public function createAnonymousIdentity();

    public function createRoleIdentity($role);

    public function createUserIdentity($user);
}