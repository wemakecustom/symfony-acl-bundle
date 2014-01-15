<?php

namespace WMC\Symfony\AclBundle\Voter\Strategy;

use WMC\Symfony\AclBundle\Model\AclTargetIdentityInterface as AclTargetIdentity;
use WMC\Symfony\AclBundle\Model\AclTargetIdentityFactoryInterface as AclTargetIdentityFactory;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface as Voter;

/**
 * This access granting strategy use a simple scheme for ACL inheritance:
 *
 * - If a Class' ACL is completely empty, we try with its parent classes.
 *
 * - If a Class Field's ACL is completely empty, we try with the same field
 *     applied to the parent classes.
 *
 * This strategy does not consider interfaces. If you need to check for
 * permissions based on the interface hierarchy, you should implement your own
 * strategy.
 */
class AclInheritanceAccessGrantingStrategy extends AclPlainAccessGrantingStrategy
{
    public function isGranted($grantees, AclTargetIdentity $target, $permissions, $fallbackVote = Voter::ACCESS_DENIED)
    {
        if (null !== $target->getObjectIdentifier()) {
            return parent::isGranted($grantees, $target, $permissions, $fallbackVote);
        }

        $factoryMethod = null === $target->getFieldName() ? 'createClassIdentity' : 'createClassFieldIdentity';
        $class = null;

        while (0 === count($this->aclProvider->searchAces(null, array($target))) && false !== ($class = get_parent_class($target->getClassName()))) {
            $target = $this->targetFactory->$factoryMethod($class, $target->getFieldName());
        }

        if (false === $class) {
            return $fallbackVote;
        }

        return parent::isGranted($grantees, $target, $permissions, $fallbackVote);
    }
}
