<?php

namespace WMC\Symfony\AclBundle\Voter\Strategy;

use WMC\Symfony\AclBundle\Model\AclTargetIdentityInterface as AclTargetIdentity;
use WMC\Symfony\AclBundle\Model\AclTargetIdentityFactoryInterface as AclTargetIdentityFactory;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface as Voter;

/**
 * This access granting strategy use a simple scheme for ACL inheritance:
 *
 * - If an Object ACL is completely empty, we try with its class.
 *
 * - If an Object Field's ACL is completely empty, we try with the same field
 *    for the Object's class.
 */
class AclMetaAccessGrantingStrategy extends AclPlainAccessGrantingStrategy
{
    public function isGranted($grantees, AclTargetIdentity $target, $permissions, $fallbackVote = Voter::ACCESS_DENIED)
    {
        if (null === $target->getObjectIdentifier()) {
            return parent::isGranted($grantees, $target, $permissions, $fallbackVote);
        }

        if (0 === count($this->aclProvider->searchAces(null, $target))) {
            $target = null === $target->getFieldName()
                ? $this->targetFactory->createClassIdentity($target->getClassName())
                : $this->targetFactory->createClassFieldIdentity($target->getClassName(), $target->getFieldName());
        }

        if (0 === count($this->aclProvider->searchAces(null, $target))) {
            return $fallbackVote;
        }

        return parent::isGranted($grantees, $target, $permissions, $fallbackVote);
    }
}
