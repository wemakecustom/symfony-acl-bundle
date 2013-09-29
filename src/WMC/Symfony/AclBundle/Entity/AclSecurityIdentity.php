<?php

namespace WMC\Symfony\AclBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use WMC\Symfony\AclBundle\Model\AclSecurityIdentityInterface;

/**
 * @ORM\Table(name="acl_security_identities")
 * @ORM\Entity
 */
class AclSecurityIdentity implements AclSecurityIdentityInterface
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
     * @ORM\ManyToOne(targetEntity="AclClass")
     * @ORM\JoinColumn(nullable=false)
     *
     * @var AclClass
     */
    private $class;

    /**
     * @ORM\Column(type="string", length=100)
     *
     * @var string
     */
    private $security_identifier;

    /**
     * @ORM\OneToMany(targetEntity="AclEntry", mappedBy="security_identity")
     *
     * @var Doctrine\Common\Collections\ArrayCollection
     */
    private $entries;

    public function __construct()
    {
        $this->entries = new ArrayCollection;
    }

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
     * Set security_identifier
     *
     * @param  string              $securityIdentifier
     * @return AclSecurityIdentity
     */
    public function setSecurityIdentifier($securityIdentifier)
    {
        $this->security_identifier = $securityIdentifier;

        return $this;
    }

    /**
     * Get security_identifier
     *
     * @return string
     */
    public function getSecurityIdentifier()
    {
        return $this->security_identifier;
    }

    public function getObjectIdentifier()
    {
        return $this->getSecurityIdentifier();
    }

    /**
     * Set class
     *
     * @param  AclClass            $class
     * @return AclSecurityIdentity
     */
    public function setClass(AclClass $class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Get class
     *
     * @return AclClass
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Get class name
     *
     * @return string
     */
    public function getClassName()
    {
        return $this->class->getName();
    }

    /**
     * Add entries
     *
     * @param  AclEntry            $entries
     * @return AclSecurityIdentity
     */
    public function addEntry(AclEntry $entries)
    {
        $this->entries[] = $entries;

        return $this;
    }

    /**
     * Remove entries
     *
     * @param AclEntry $entries
     */
    public function removeEntry(AclEntry $entries)
    {
        $this->entries->removeElement($entries);
    }

    /**
     * Get entries
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEntries()
    {
        return $this->entries;
    }

    public function equals(AclSecurityIdentityInterface $identity)
    {
        return $this->getClassName() == $identity->getClassName()
            && $this->getObjectIdentifier() == $identity->getObjectIdentifier();
    }
}
