<?php

namespace WMC\Symfony\AclBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Acl\Dbal\Schema;

/**
 * @ORM\Table(name="acl_classes")
 * @ORM\Entity
 */
class AclClass
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
     * @ORM\Column(type="string", length=200)
     *
     * @var  string
     */
    private $name;
}
