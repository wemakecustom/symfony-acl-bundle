<?php

namespace WMC\Symfony\AclBundle\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

use Psr\Log\LoggerInterface as Logger;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface as Token;

use WMC\Symfony\AclBundle\Model\AclPermissionMapInterface as PermissionMap;

use WMC\Symfony\AclBundle\Model\AclProviderInterface as AclProvider;
use WMC\Symfony\AclBundle\Model\AclAccessGrantingStrategyInterface as AclAccessGrantingStrategy;
use WMC\Symfony\AclBundle\Model\AclTargetIdentityInterface as AclTargetIdentity;

/**
 * This voter can be used as a base class for implementing your own permissions.
 *
 * This voter supports PermissionMaps and Roles.
 *
 * ACL Inheritance strategy should be performed through the
 * AccessGrantingStrategy.
 */
class AclVoter implements VoterInterface
{
    /**
     * @var AclProvider
     */
    protected $aclProvider;

    /**
     * @var PermissionMap
     */
    protected $permissionMap;

    /**
     * @var AclAccessGrantingStrategy
     */
    protected $strategy;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * It is your responsibility to ensure the AclAccessGrantingStrategy is
     * consistent with the other dependencies injected here.
     */
    public function __construct(AclProvider $aclProvider, PermissionMap $permissionMap, AclAccessGrantingStrategy $strategy, Logger $logger = null)
    {
        $this->aclProvider   = $aclProvider;
        $this->permissionMap = $permissionMap;
        $this->strategy      = $strategy;
        $this->logger        = $logger;
    }

    public function supportsAttribute($attribute)
    {
        return $this->permissionMap->contains($attribute);
    }

    public function vote(Token $token, $target, array $attributes)
    {
        if (null === $target) {
            return self::ACCESS_ABSTAIN;
        }

        $targetIdentity = $this->aclProvider->extractTargetIdentity($target);

        if (!$this->supportsClass($targetIdentity->getClassName())) {
            $this->debug('Target identity not supported. Abstaining.');

            return self::ACCESS_ABSTAIN;
        }

        $permissionsLists = array_map(array($this->permissionMap, 'getPermissions'), $attributes);
        $permissions = array_reduce($permissionsLists, 'array_merge', array());
        $permissions = array_unique($permissions);

        if (0 === count($permissions)) {
            $this->debug('Attribute set not supported or unknown. Abstaining.');

            return self::ACCESS_ABSTAIN;
        }

        $grantees = array_merge(array($token), $token->getRoles());

        return $this->strategy->isGranted($this->aclProvider, $grantees, $targetIdentity, $permissions);
    }

    protected function debug($message)
    {
        if (null !== $this->logger) {
            $this->logger->debug($message);
        }
    }

    /**
     * You can override this method when writing a voter for a specific domain
     * class.
     *
     * @param string $class The class name
     *
     * @return Boolean
     */
    public function supportsClass($class)
    {
        return true;
    }
}
