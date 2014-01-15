<?php

namespace WMC\Symfony\AclBundle\Voter\Strategy;

use WMC\Symfony\AclBundle\Model\AclTargetIdentityInterface as AclTargetIdentity;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface as Voter;

/**
 * This access granting strategy use a simple scheme for ACL inheritance:
 *
 * - If an Object Field ACL is completely empty, we try with its object.
 *
 * - If a Class Field's ACL is completely empty, we try with its class.
 */
class AclFieldErasureAccessGrantingStrategy extends AclPlainAccessGrantingStrategy
{
    public function isGranted($grantees, AclTargetIdentity $target, $permissions, $fallbackVote = Voter::ACCESS_DENIED)
    {
        if (null === $target->getFieldName()) {
            return parent::isGranted($grantees, $target, $permissions, $fallbackVote);
        }

        if (0 === count($this->aclProvider->searchAces(null, $target))) {
            $target = null !== $target->getObjectIdentifier()
                ? $this->targetFactory->createObjectIdentity(array($target->getClassName(), $target->getObjectIdentifier()))
                : $this->targetFactory->createClassIdentity($target->getClassName());
        }

        if (0 === count($this->aclProvider->searchAces(null, $target))) {
            return $fallbackVote;
        }

        return parent::isGranted($grantees, $target, $permissions, $fallbackVote);
    }
}
