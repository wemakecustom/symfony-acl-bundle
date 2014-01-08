<?php

namespace WMC\Symfony\AclBundle\Provider;

use WMC\Symfony\AclBundle\Model\AclProviderInterface;

use WMC\Symfony\AclBundle\Model\AclTargetIdentityFactoryInterface as TargetIdentityFactory;
use WMC\Symfony\AclBundle\Model\AclSecurityIdentityFactoryInterface as SecurityIdentityFactory;

abstract class AbstractAclProvider implements AclProviderInterface
{
    /**
     * @var SecurityIdentityFactory
     */
    protected $securityIdentityFactory;

    /**
     * @var TargetIdentityFactory
     */
    protected $targetIdentityFactory;

    public function __construct(SecurityIdentityFactory $securityIdentityFactory, TargetIdentityFactory $targetIdentityFactory)
    {
        $this->securityIdentityFactory = $securityIdentityFactory;
        $this->targetIdentityFactory   = $targetIdentityFactory;
    }

    public function extractSecurityIdentity($grantee)
    {
        return $this->securityIdentityFactory->extractSecurityIdentity($grantee);
    }

    public function extractTargetIdentity($target)
    {
        return $this->targetIdentityFactory->extractTargetIdentity($target);
    }
}