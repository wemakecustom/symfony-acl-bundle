<?php

namespace WMC\Symfony\AclBundle\Model;

/**
 * This class represents an individual entry in an ACL.
 *
 * @author Mathieu Lemoine <mathieu@wemakecustom.com>
 */
interface AclEntryInterface
{
    /**
     * The grantee of this ACE
     *
     * @return SecurityIdentityInterface
     */
    public function getSecurityIdentity();

    /**
     * The target of this ACE
     *
     * @return TargetIdentityInterface
     */
    public function getTargetIdentity();

    /**
     * The permission label of this ACE
     *
     * @return string
     */
    public function getPermission();
}
