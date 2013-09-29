<?php

namespace WMC\Symfony\AclBundle\Provider;

use WMC\Symfony\AclBundle\Model\AclMutableProviderInterface;

/**
 * Default Doctrine ACE provider
 *
 * @author Mathieu Lemoine <mathieu@wemakecustom.com>
 */
class DoctrineMutableProvider extends AbstractAclProvider implements AclMutableProviderInterface
{
    /**
     * @var ObjectRepository
     */
    protected $repository;

    public function __construct(ObjectManager $manager, $acl_entry_class)
    {
        $this->repository = $manager->getRepository($acl_entry_class);
    }

    /**
     * Returns the ACEs matching for the pair Identity/Object
     *
     * @return EntryInterface[]
     */
    public function findAcl($grantee, $target)
    {

    }

    /**
     * Returns the ACLs matching the research
     *
     * The intended goal for this method is provided an efficient way to search
     * for ACEs. This could mean either using a local cache or directly querying
     * the storage backend, whichever is more adapted to your use case.
     */
    public function searchAcls(array $grantees = array(), array $targets = array(), array $permissions = array());

    /**
     * Inserts a new ACE
     *
     * This method is idempotent.
     */
    public function createAcl($grantee, $target, $permission);

    /**
     * Deletes an ACE
     *
     * This method is idempotent.
     */
    public function deleteAcl($grantee, $target, $permission);
}
