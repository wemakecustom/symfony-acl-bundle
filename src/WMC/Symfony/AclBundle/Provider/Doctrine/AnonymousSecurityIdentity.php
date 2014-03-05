<?php

namespace WMC\Symfony\AclBundle\Provider\Doctrine;

use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;

/**
 * SecurityIdentity for an AnonymousToken
 */
final class AnonymousSecurityIdentity extends AbstractSecurityIdentity
{
    protected $kind = self::KIND_ANONYMOUS;

    public function __construct()
    {
        parent::__construct(self::KIND_ANONYMOUS 'Symfony\Component\Security\Core\Authentication\Token\AnonymousToken', null);
    }

    public function getKind()
    {
        return self::KIND_ANONYMOUS;
    }
}
