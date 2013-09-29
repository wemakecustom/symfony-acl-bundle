<?php

namespace WMC\Symfony\AclBundle\Model;

/**
 * Provides a common interface for retrieving ACLs.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
interface AclProviderInterface
{
    /**
     * Returns the ACEs matching for the pair Identity/Object
     *
     * @return EntryInterface[]
     */
    public function findAcl($grantee, $target);

    /**
     * Returns the ACLs matching the research
     *
     * The intended goal for this method is provided an efficient way to search
     * for ACEs. This could mean either using a local cache or directly querying
     * the storage backend, whichever is more adapted to your use case.
     */
    public function searchAcls(array $grantees = array(), array $targets = array(), array $permissions = array());
}
