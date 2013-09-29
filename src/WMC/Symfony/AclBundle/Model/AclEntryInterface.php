<?php

namespace WMC\Symfony\AclBundle\Model;

/**
 * This class represents an individual entry in the ACL list.
 *
 * @author Mathieu Lemoine <mathieu@wemakecustom.com>
 */
interface AclEntryInterface
{
    /**
     * The primary key of this ACE
     *
     * @return integer
     */
    public function getId();

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
