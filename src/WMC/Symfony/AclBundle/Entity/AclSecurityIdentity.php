<?php

namespace WMC\Symfony\AclBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Acl\Dbal\Schema;

/**
 * @ORM\Table(name="acl_security_identities")
 * @ORM\Entity
 */
class AclSecurityIdentity
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
     * @ORM\ManyToOne(targetEntity="AclClass")
     * @ORM\JoinColumn(nullable=false)
     *
     * @var  AclClass
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
}
