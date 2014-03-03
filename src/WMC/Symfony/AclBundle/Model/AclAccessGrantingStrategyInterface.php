<?php

namespace WMC\Symfony\AclBundle\Model;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface as Voter;

interface AclAccessGrantingStrategyInterface
{
    public function isGranted($grantees, AclTargetIdentityInterface &$target, $permissions, $fallbackVote = Voter::ACCESS_DENIED);
}
