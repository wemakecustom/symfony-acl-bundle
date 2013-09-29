<?php

namespace WMC\Symfony\AclBundle\Model;

/**
 * Provides support for creating and storing ACL instances.
 *
 * @author Mathieu Lemoine <mathieu@wemakecustom.com>
 */
interface MutableAclProviderInterface extends AclProviderInterface
{
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
