<?php

namespace WMC\Symfony\AclBundle\Model;

/**
 * This method can be implemented by domain objects which you want to store
 * ACLs for if they do not have a getId() method, or getId() does not return
 * a unique identifier.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
interface AclTargetObjectInterface
{
    /**
     * Returns a unique identifier for this domain object.
     *
     * @return string
     */
    public function getObjectIdentifier();
}
