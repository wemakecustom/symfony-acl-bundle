<?php

namespace WMC\Symfony\AclBundle\Model;

/**
 * Provides a common interface for retrieving ACLs.
 */
interface AclProviderInterface
{
    /**
     * Convert a Security object (potentiel grantee) to a
     * AclSecurityIdentityInterface
     *
     * Accepts:
     *  - null (equivalent to anonymous token)
     *  - Role name
     *  - Role instance
     *  - Token (incl. anonymous)
     *  - User
     *  - AclSecurityIdentityInterface
     */
    public function extractSecurityIdentity($grantee);

    /**
     * Convert a domain object (potentiel target) to a
     * AclTargetObjectInterface
     *
     * Accepts:
     *  - class name
     *  - object
     *  - array(object, field name)
     *  - array(class name, field name)
     *  - AclTargetObjectInterface
     */
    public function extractTargetIdentity($target);

    /**
     * Returns the ACEs matching for the pair Grantee/Target
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
    public function searchAces(array $grantees = array(), array $targets = array(), array $permissions = array());
}
