<?php

namespace WMC\Symfony\AclBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use WMC\Symfony\AclBundle\Model\AclEntryInterface;

/**
 * @ORM\Table(name="acl_entries")
 * @ORM\Entity
 */
class AclEntry implements AclEntryInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     *
     * @var integer
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AclTargetIdentity", inversedBy="entries")
     * @ORM\JoinColumn(nullable=false)
     *
     * @var AclTargetIdentity
     */
    private $target_identity;

    /**
     * @ORM\ManyToOne(targetEntity="AclSecurityIdentity", inversedBy="entries")
     * @ORM\JoinColumn(nullable=false)
     *
     * @var AclSecurityIdentity
     */
    private $security_identity;

    /**
     * @ORM\Column(type="string", length=50)
     *
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
     * Set target_identity
     *
     * @param  AclTargetIdentity $targetIdentity
     * @return AclEntry
     */
    public function setTargetIdentity(AclTargetIdentity $targetIdentity)
    {
        $this->target_identity = $targetIdentity;

        return $this;
    }

    /**
     * Get target_identity
     *
     * @return AclTargetIdentity
     */
    public function getTargetIdentity()
    {
        return $this->target_identity;
    }

    /**
     * Set security_identity
     *
     * @param  AclSecurityIdentity $securityIdentity
     * @return AclEntry
     */
    public function setSecurityIdentity(AclSecurityIdentity $securityIdentity)
    {
        $this->security_identity = $securityIdentity;

        return $this;
    }

    /**
     * Get security_identity
     *
     * @return AclSecurityIdentity
     */
    public function getSecurityIdentity()
    {
        return $this->security_identity;
    }
}
