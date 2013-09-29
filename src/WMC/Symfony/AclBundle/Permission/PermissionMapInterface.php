<?php

namespace WMC\Symfony\AclBundle\Permission;

interface PermissionMapInterface
{
    /**
     * Returns an array of attributes provided by this permission.
     *
     * @param  string $permission
     * @return array
     */
    public function getAttributes($permission);

    /**
     * Returns an array of permissions granting this attribute.
     *
     * @param  string $attribute
     * @return array
     */
    public function getPermissions($attribute);
}
