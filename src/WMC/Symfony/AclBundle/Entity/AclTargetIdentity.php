<?php

namespace WMC\Symfony\AclBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use WMC\Symfony\AclBundle\Model\AclTargetIdentityInterface;

/**
 * @ORM\Table(name="acl_object_identities")
 * @ORM\Entity
 */
class AclTargetIdentity implements AclTargetIdentityInterface
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
     * @ORM\ManyToMany(targetEntity="AclTargetIdentity", inversedBy="descendants")
     * @ORM\JoinTable(
     *     name="acl_object_identity_ancestors",
     *     joinColumns={@ORM\JoinColumn(name="object_identity_id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="ancestor_id")}
     * )
     *
     * @var Doctrine\Common\Collections\ArrayCollection
     */
    private $ancestors;

    /**
     * @ORM\ManyToMany(targetEntity="AclTargetIdentity", mappedBy="ancestors")
     *
     * @var Doctrine\Common\Collections\ArrayCollection
     */
    private $descendants;

    /**
     * @ORM\ManyToOne(targetEntity="AclClass")
     * @ORM\JoinColumn(nullable=false)
     *
     * @var AclClass
     */
    private $class;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     *
     * @var string
     */
    private $object_identifier;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     *
     * @var string
     */
    private $field_name;

    /**
     * @ORM\OneToMany(targetEntity="AclEntry", mappedBy="object_identity")
     *
     * @var Doctrine\Common\Collections\ArrayCollection
     */
    private $entries;

    public function __construct()
    {
        $this->ancestors   = new ArrayCollection;
        $this->descendants = new ArrayCollection;
        $this->entries     = new ArrayCollection;
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
     * Set object_identifier
     *
     * @param  string            $objectIdentifier
     * @return AclTargetIdentity
     */
    public function setObjectIdentifier($objectIdentifier)
    {
        $this->object_identifier = $objectIdentifier;

        return $this;
    }

    /**
     * Get object_identifier
     *
     * @return string
     */
    public function getObjectIdentifier()
    {
        return $this->object_identifier;
    }

    /**
     * Set field_name
     *
     * @param  string            $fieldName
     * @return AclTargetIdentity
     */
    public function setFieldName($fieldName)
    {
        $this->field_name = $fieldName;

        return $this;
    }

    /**
     * Get field_name
     *
     * @return string
     */
    public function getFieldName()
    {
        return $this->field_name;
    }

    /**
     * Add ancestors
     *
     * @param  AclTargetIdentity $ancestors
     * @return AclTargetIdentity
     */
    public function addAncestor(AclTargetIdentity $ancestors)
    {
        $this->ancestors[] = $ancestors;

        return $this;
    }

    /**
     * Remove ancestors
     *
     * @param AclTargetIdentity $ancestors
     */
    public function removeAncestor(AclTargetIdentity $ancestors)
    {
        $this->ancestors->removeElement($ancestors);
    }

    /**
     * Get ancestors
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAncestors()
    {
        return $this->ancestors;
    }

    /**
     * Add descendants
     *
     * @param  AclTargetIdentity $descendants
     * @return AclTargetIdentity
     */
    public function addDescendant(AclTargetIdentity $descendants)
    {
        $this->descendants[] = $descendants;

        return $this;
    }

    /**
     * Remove descendants
     *
     * @param AclTargetIdentity $descendants
     */
    public function removeDescendant(AclTargetIdentity $descendants)
    {
        $this->descendants->removeElement($descendants);
    }

    /**
     * Get descendants
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDescendants()
    {
        return $this->descendants;
    }

    /**
     * Set class
     *
     * @param  AclClass          $class
     * @return AclTargetIdentity
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
     * @param  AclEntry          $entries
     * @return AclTargetIdentity
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
}
