<?php

namespace WMC\Symfony\AclBundle\Voter\Strategy;

use WMC\Symfony\AclBundle\Model\AclTargetIdentityInterface as AclTargetIdentity;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface as Voter;

/**
 * This access granting strategy does not provide any kind of ACL fallback.
 *
 * $fallbackVote is thus ignored.
 */
class AclPlainAccessGrantingStrategy extends AbstractAclAccessGrantingStrategy
{
    public function isGranted($grantees, AclTargetIdentity $target, $permissions, $fallbackVote = null)
    {
        if (count($this->aclProvider->searchAces($grantees, array($target), $permissions))) {
            $this->debug('ACL found, permission granted. Voting to grant.');

            return Voter::ACCESS_GRANTED;
        }

        $this->debug('No ACL found. Voting to deny.');

        return Voter::ACCESS_DENIED;
    }
}
