<?php

namespace WMC\Symfony\AclBundle\Model;

/**
 * This interface provides an additional level of indirection, so that
 * we can work with abstracted versions of security objects and do
 * not have to save the entire objects.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
interface AclSecurityIdentityInterface
{
    /**
     * Returns the name of the class targeted
     *
     * @return string
     */
    public function getClassName();

    /**
     * Returns the ID of the security object
     *
     * @return string
     */
    public function getObjectIdentifier();

    /**
     * This method is used to compare two security identities in order to
     * not rely on referential equality.
     *
     * @param SecurityIdentityInterface $identity
     */
    public function equals(AclSecurityIdentityInterface $identity);
}
