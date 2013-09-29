<?php

namespace WMC\Symfony\AclBundle\Model;

/**
 * @author Mathieu Lemoine <mathieu@wemakecustom.com>
 */
interface TargetIdentityInterface
{
    /**
     * Returns the name of the class targeted
     *
     * @return string
     */
    public function getClassName();

    /**
     * Returns the ID of the target object
     *
     * @return null|string
     */
    public function getId();

    /**
     * Returns the name of the target field.
     *
     * @return null|string
     */
    public function getObjectIdentifier();
}
