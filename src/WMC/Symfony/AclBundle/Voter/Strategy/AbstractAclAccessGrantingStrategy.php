<?php

namespace WMC\Symfony\AclBundle\Voter\Strategy;

use Psr\Log\LoggerInterface as Logger;

use WMC\Symfony\AclBundle\Model\AclAccessGrantingStrategyInterface;

use WMC\Symfony\AclBundle\Model\AclTargetIdentityInterface as AclTargetIdentity;
use WMC\Symfony\AclBundle\Model\AclTargetIdentityFactoryInterface as AclTargetIdentityFactory;

use WMC\Symfony\AclBundle\Model\AclProviderInterface as AclProvider;

abstract class AbstractAclAccessGrantingStrategy implements AclAccessGrantingStrategyInterface
{
    /**
     * @var AclProvider
     */
    protected $aclProvider;
    
    /**
     * @var AclTargetIdentityFactory
     */
    protected $targetFactory;

    /**
     * @var Logger
     */
    protected $logger;

    public function __construct(AclProvider $aclProvider, AclTargetIdentityFactory $targetFactory, Logger $logger = null)
    {
        $this->aclProvider   = $aclProvider;
        $this->targetFactory = $targetFactory;
        $this->logger        = $logger;
    }

    protected function isACLEmpty(array $grantees, AclTargetIdentity $target)
    {
        return 0 === count($this->aclProvider->searchAces($grantees, $target));
    }

    protected function debug($message)
    {
        if (null !== $this->logger) {
            $this->logger->debug($message);
        }
    }
}