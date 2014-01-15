<?php

namespace WMC\Symfony\AclBundle\Voter\Strategy;

use WMC\Symfony\AclBundle\Model\AclTargetIdentityInterface as AclTargetIdentity;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface as Voter;

/**
 * DISCLAIMER: This strategy's first intended use is as a base or example
 * implementation or more complex strategies. However, if it suits your need, go
 * ahead and use it as-is.
 *
 * This access granting strategy combines three basic strategies for ACL fallback.
 *
 * We look for the first non-empty ACL in this order (each applied recursively):
 *
 * - The target itself
 *
 * - If the target has an Object identifier, we will try with the associated
 *    class (Meta strategy).
 *
 * - If the target has a Field identifier, we will try with the associated upper
 *    nesting level (FieldErasure strategy).
 *
 * - If the target doesn't have an Object identifier, we will try with the
 *    parent class of the target (Inheritance strategy).
 *
 * Each step is applied through queued recursion. For example, if we check
 * the ACL for the target [C, O, F], we will check the ACLs in this order (n
 * denotes null, PC denotes the parent class of C), we stop at the first
 * non-empty ACL to reach a decision:
 *
 *  1. [C, O, F]
 *  2. [C, n, F]     Meta
 *  3. [C, O, n]     Field Erasure
 *     ---           Inheritance (does not apply)
 *     ---           Recursion on [C, n, F] (2): Meta (does not apply)
 *  4. [C, n, n]     Recursion on [C, n, F] (2): Field Erasure
 *  5. [PC, n, F]    Recursion on [C, n, F] (2): Inheritance
 *  6. -C, n, n-     Recursion on [C, O, n] (3): Meta (Identical to 4: Skipped, no recursion)
 *     ---           Recursion on [C, O, n] (3): Field Erasure (does not apply)
 *     ---           Recursion on [C, O, n] (3): Inheritance (does not apply)
 *     ---           Recursion on [C, n, n] (4): Meta (does not apply)
 *     ---           Recursion on [C, n, n] (4): Field Erasure (does not apply)
 *  7. [PC, n, n]    Recursion on [C, n, n] (4): Inheritance
 *     ---           Recursion on [PC, n, F] (5): Meta (does not apply)
 *  8. -PC, n, n-    Recursion on [PC, n, F] (5): Field Erasure (Identical to 7: Skipped, no recursion)
 *  9. [PPC, n, F]   Recursion on [PC, n, F] (5): Inheritance (does not apply)
 *
 * => From here the pattern will always be the same until the class inheritance
 * line is exhausted. Each class in the inheritance line will be checked. First
 * with the field name, then without, similarly to recursion passes on steps 4
 * and 5.
 */
class AclComplexAccessGrantingStrategy extends AclPlainAccessGrantingStrategy
{
    public function isGranted($grantees, AclTargetIdentity $target, $permissions, $fallbackVote = Voter::ACCESS_DENIED)
    {
        $queue = array();
        $visited = array();

        while (null !== $target) {
            $result = $this->checkAccess($grantees, $target, $permissions, $fallbackVote, $queue, $visited);

            if (Voter::ACCESS_ABSTAIN !== $result) {
                return $result;
            }

            $target = array_shift($queue);
        }

        return $fallbackVote;
    }

    protected function checkAccess($grantees, AclTargetIdentity &$target, $permissions, $fallbackVote, &$queue, &$visisted)
    {
        if ($this->hasBeenVisited($target, $visited)) {
            return Voter::ACCESS_ABSTAIN;
        }

        $this->markAsVisited($target, $visited);

        if (0 !== count($this->aclProvider->searchAces(null, array($target)))) {
            return parent::isGranted($grantees, $target, $permissions, $fallbackVote);
        }

        if (null !== $target->getObjectIdentifier()) {
            $queue[] = null === $target->getFieldName()
                ? $this->targetFactory->createClassIdentity($target->getClassName())
                : $this->targetFactory->createClassFieldIdentity($target->getClassName(), $target->getFieldName());
        }

        if (null !== $target->getFieldName()) {
            $queue[] = null !== $target->getObjectIdentifier()
                ? $this->targetFactory->createObjectIdentity(array($target->getClassName(), $target->getObjectIdentifier()))
                : $this->targetFactory->createClassIdentity($target->getClassName());
        }

        if (null === $target->getObjectIdentifier() && false !== ($class = get_parent_class($target->getClassName()))) {
            $queue[] = null === $target->getFieldName()
                ? $this->targetFactory->createClassIdentity($class)
                : $this->targetFactory->createClassFieldIdentity($class, $target->getFieldName());
        }

        return Voter::ACCESS_ABSTAIN;
    }

    protected function getKeyForIdentityPart($part)
    {
        return (null === $part ? '0' : '1').$part;
    }

    protected function markAsVisited(AclTargetIdentity $target, array &$visited)
    {
        $visited
            [$this->getKeyForIdentityPart($target->getClassName())]
            [$this->getKeyForIdentityPart($target->getObjectIdentifier())]
            [$this->getKeyForIdentityPart($target->getFieldName())]
            = true;
    }

    protected function hasBeenVisited(AclTargetIdentity $target, array &$visited)
    {
        return isset($visited
                     [$this->getKeyForIdentityPart($target->getClassName())]
                     [$this->getKeyForIdentityPart($target->getObjectIdentifier())]
                     [$this->getKeyForIdentityPart($target->getFieldName())]);

    }
}
