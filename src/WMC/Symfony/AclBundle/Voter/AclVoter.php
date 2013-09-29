<?php

namespace WMC\Symfony\AclBundle\Voter;

use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use WMC\Symfony\AclBundle\Model\AclProviderInterface;
use WMC\Symfony\AclBundle\Permission\PermissionMapInterface;

/**
 * This voter can be used as a base class for implementing your own permissions.
 */
class AclVoter implements VoterInterface
{
    private $aclProvider;
    private $permissionMap;
    private $logger;

    public function __construct(AclProviderInterface $aclProvider, PermissionMapInterface $permissionMap, LoggerInterface $logger = null)
    {
        $this->aclProvider = $aclProvider;
        $this->permissionMap = $permissionMap;
        $this->logger = $logger;
    }

    public function supportsAttribute($attribute)
    {
        return $this->permissionMap->contains($attribute);
    }

    public function vote(TokenInterface $token, $target, array $attributes)
    {
        $target_identity = $this->aclProvider->extractTargetIdentity($target);

        if (!$this->supportsClass($target_identity->getClassName())) {
            return self::ACCESS_ABSTAIN;
        }

        $permissions_lists = array_map(array($this->permissionMap, 'getPermissions'), $attributes);
        $permissions = array_reduce($permissions_lists, 'array_merge', array());
        $permissions = array_unique($permissions);

        if (0 === count($permissions)) {
            return self::ACCESS_ABSTAIN;
        }

        if (count($this->aclProvider->searchAces(array($token), array($target_identity), $permissions))) {
            $this->debug('ACL found, permission granted. Voting to grant access');

            return self::ACCESS_GRANTED;
        }

        $this->debug('No ACL found for the object identity. Voting to deny access.');

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
