<?php

namespace WMC\Symfony\AclBundle\Model;

/**
 * This class represents an individual entry in the ACL list.
 *
 * @author Mathieu Lemoine <mathieu@wemakecustom.com>
 */
interface EntryInterface extends \Serializable
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
    public function getGrantee();

    /**
     * The target of this ACE
     *
     * @return TargetIdentityInterface
     */
    public function getTarget();

    /**
     * The permission label of this ACE
     *
     * @return string
     */
    public function getPermission();
}
