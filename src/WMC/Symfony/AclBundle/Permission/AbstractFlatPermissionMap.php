<?php

namespace WMC\Symfony\AclBundle\Permission;

abstract class AbstractFlatPermissionMap implements PermissionMapInterface
{
    protected $attributes = array();

    protected $flat_map = null;

    /**
     * {@inheritDoc}
     */
    public function getAttributes($permission)
    {
        if (null === $this->flat_map) {
            $this->buildMap();
        }

        if (!isset($this->flat_map[$permission])) {
            return array();
        }

        return $this->flat_map[$permission];
    }

    /**
     * {@inheritDoc}
     */
    public function getPermissions($attribute)
    {
        if (null === $this->flat_map) {
            $this->buildMap();
        }

        $permissions = array();

        foreach ($this->flat_map as $permission => $attributes) {
            if (in_array($attribute, $attributes)) {
                $permissions[] = $permission;
            }
        }

        return $permissions;
    }

    protected function addPermission($permission, array $attributes = array())
    {
        if (!isset($this->attributes[$permission])) {
            $this->attributes[$permission] = array();
        }

        foreach ($attributes as $attribute) {
            $this->attributes[$permission][] = $attribute;
        }
    }

    protected function buildMap()
    {
        $this->flat_map = array();

        foreach ($this->attributes as $permission => $attributes) {
            $this->fill($permission, $attributes);
            $this->flat_map[$permission] = array_unique($attributes);
        }
    }

    protected function fill($permission, &$attributes)
    {
        if (isset($this->attributes[$permission])) {
            $attributes[] = $permission;

            foreach ($this->attributes[$permission] as $attribute) {
                $this->fill($attribute, $attributes);
            }
        } else {
            throw new InvalidPermissionMapException("Permission $permission does not exist");
        }
    }
}
