<?php

namespace WMC\Symfony\AclBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Acl\Dbal\Schema;

/**
 * @ORM\Table(name="acl_object_identities")
 * @ORM\Entity
 */
class AclObjectIdentity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     *
     * @var  integer
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="AclObjectIdentity", inversedBy="descendants")
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
     * @ORM\ManyToMany(targetEntity="AclObjectIdentity", mappedBy="ancestors")
     *
     * @var Doctrine\Common\Collections\ArrayCollection
     */
    private $descendants;

    /**
     * @ORM\ManyToOne(targetEntity="AclClass")
     * @ORM\JoinColumn(nullable=false)
     *
     * @var  AclClass
     */
    private $class;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     *
     * @var  string
     */
    private $object_identifier;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     *
     * @var  string
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
}
