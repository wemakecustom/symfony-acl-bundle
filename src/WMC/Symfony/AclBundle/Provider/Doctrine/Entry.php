<?php

namespace WMC\Symfony\AclBundle\Provider\Doctrine;

use WMC\Symfony\AclBundle\Model\AclEntryInterface;

class Entry implements AclEntryInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var AclTargetIdentity
     */
    private $targetIdentity;

    /**
     * @var AclSecurityIdentity
     */
    private $securityIdentity;

    /**
     * @var string
     */
    private $permission;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set permission
     *
     * @param  string   $permission
     * @return AclEntry
     */
    public function setPermission($permission)
    {
        $this->permission = $permission;

        return $this;
    }

    /**
     * Get permission
     *
     * @return string
     */
    public function getPermission()
    {
        return $this->permission;
    }

    /**
     * Set targetIdentity
     *
     * @param  AclTargetIdentity $targetIdentity
     * @return AclEntry
     */
    public function setTargetIdentity(AclTargetIdentity $targetIdentity)
    {
        $this->targetIdentity = $targetIdentity;

        return $this;
    }

    /**
     * Get targetIdentity
     *
     * @return AclTargetIdentity
     */
    public function getTargetIdentity()
    {
        return $this->targetIdentity;
    }

    /**
     * Set securityIdentity
     *
     * @param  AclSecurityIdentity $securityIdentity
     * @return AclEntry
     */
    public function setSecurityIdentity(AclSecurityIdentity $securityIdentity)
    {
        $this->securityIdentity = $securityIdentity;

        return $this;
    }

    /**
     * Get securityIdentity
     *
     * @return AclSecurityIdentity
     */
    public function getSecurityIdentity()
    {
        return $this->securityIdentity;
    }
}
