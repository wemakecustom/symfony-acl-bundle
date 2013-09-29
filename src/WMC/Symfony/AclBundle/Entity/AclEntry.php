<?php

namespace WMC\Symfony\AclBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Acl\Dbal\Schema;

/**
 * @ORM\Table(name="acl_entries")
 * @ORM\Entity
 */
class AclEntry
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
     * @ORM\ManyToOne(targetEntity="AclObjectIdentity", inversedBy="entries")
     * @ORM\JoinColumn(nullable=false)
     *
     * @var AclObjectIdentity
     */
    private $object_identity;

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
}
