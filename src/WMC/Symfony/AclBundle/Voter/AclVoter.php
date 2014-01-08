<?php

namespace WMC\Symfony\AclBundle\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

use Psr\Log\LoggerInterface as Logger;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface as Token;
use WMC\Symfony\AclBundle\Model\AclProviderInterface as AclProvider;
use WMC\Symfony\AclBundle\Permission\PermissionMapInterface as PermissionMap;

/**
 * This voter can be used as a base class for implementing your own permissions.
 */
class AclVoter implements VoterInterface
{
    /**
     * @var AclProvider
     */
    private $aclProvider;

    /**
     * @var PermissionMap
     */
    private $permissionMap;

    /**
     * @var Logger
     */
    private $logger;

    public function __construct(AclProvider $aclProvider, PermissionMap $permissionMap, Logger $logger = null)
    {
        $this->aclProvider   = $aclProvider;
        $this->permissionMap = $permissionMap;
        $this->logger        = $logger;
    }

    public function supportsAttribute($attribute)
    {
        return $this->permissionMap->contains($attribute);
    }

    public function vote(Token $token, $target, array $attributes)
    {
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

        if (count($this->aclProvider->searchAces(array($token), array($targetIdentity), $permissions))) {
            $this->debug('ACL found, permission granted. Voting to grant.');

            return self::ACCESS_GRANTED;
        }

        $this->debug('No ACL found. Voting to deny.');

        return self::ACCESS_DENIED;
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
