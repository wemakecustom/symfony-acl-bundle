<?php

namespace WMC\Symfony\AclBundle\Model;

/**
 * Provides a common interface for retrieving ACLs.
 */
interface AclProviderInterface
{
    /**
     * @see AclTargetIdentityFactoryInterface#extractTargetIdentity
     */
    public function extractTargetIdentity($target);

    /**
     * @see AclSecurityIdentityFactoryInterface#extractSecurityIdentity
     */
    public function extractSecurityIdentity($grantee);

    /**
     * Returns the ACEs matching for the pair Grantee/Target
     *
     * @return AclEntryInterface[]
     */
    public function findAcl($grantee, $target);

    /**
     * Returns the ACLs matching the research
     *
     * The intended goal for this method is to provid an efficient way of
     * searching for ACEs. This could mean either using a local cache or
     * directly querying the storage backend, whichever is more adapted to your
     * use case.
     *
     * If any of the parameters is null, it will be interpreted as a wildcard
     * (i.e. no restrictions on it).
     *
     * The goal of this method is not to enforce any kind of ACLs inheritence,
     * but only to provide a fast bulk operation primitive to look for a set of
     * ACLs.
     *
     * @return AclEntryInterface[]
     */
    public function searchAces(array $grantees = null, array $targets = null, array $permissions = null);
}
