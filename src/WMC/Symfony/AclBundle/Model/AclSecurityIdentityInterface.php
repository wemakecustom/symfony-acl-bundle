<?php

namespace WMC\Symfony\AclBundle\Model;

/**
 * This interface provides an additional level of indirection, so we can work
 * with abstracted versions of security objects and do not have to save the
 * entire objects.
 */
interface AclSecurityIdentityInterface
{
    const KIND_ANONYMOUS = 'ANONYMOUS';
    const KIND_ROLE      = 'ROLE';
    const KIND_USER      = 'USER';

    /**
     * Returns the kind of SecurityObject (Anonymous, Role or User)
     *
     * @return string
     */
    public function getKind();

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
