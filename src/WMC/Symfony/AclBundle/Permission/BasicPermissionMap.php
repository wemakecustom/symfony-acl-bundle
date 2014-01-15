<?php

namespace WMC\Symfony\AclBundle\Permission;

class BasicPermissionMap extends AbstractFlatPermissionMap implements PermissionMapInterface
{
    const PERMISSION_VIEW        = 'VIEW';
    const PERMISSION_EDIT        = 'EDIT';
    const PERMISSION_CREATE      = 'CREATE';
    const PERMISSION_DELETE      = 'DELETE';
    const PERMISSION_UNDELETE    = 'UNDELETE';
    const PERMISSION_OPERATOR    = 'OPERATOR';
    const PERMISSION_MASTER      = 'MASTER';
    const PERMISSION_OWNER       = 'OWNER';

    public function __construct()
    {
        $this->addPermission(self::PERMISSION_VIEW);

        $this->addPermission(self::PERMISSION_EDIT, array(
            self::PERMISSION_VIEW
        ));

        $this->addPermission(self::PERMISSION_CREATE);

        $this->addPermission(self::PERMISSION_DELETE);

        $this->addPermission(self::PERMISSION_UNDELETE);

        $this->addPermission(self::PERMISSION_OPERATOR, array(
            self::PERMISSION_EDIT,
            self::PERMISSION_CREATE,
            self::PERMISSION_DELETE,
            self::PERMISSION_UNDELETE,
        ));

        $this->addPermission(self::PERMISSION_MASTER, array(
            self::PERMISSION_OPERATOR
        ));

        $this->addPermission(self::PERMISSION_OWNER, array(
            self::PERMISSION_MASTER
        ));
    }
}
