<?php

namespace WMC\Symfony\AclBundle\Provider\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;

use WMC\Symfony\AclBundle\Model\AclMutableProviderInterface;

use WMC\Symfony\AclBundle\Provider\AbstractAclProvider;

use WMC\Symfony\AclBundle\Model\AclTargetIdentityFactoryInterface as TargetIdentityFactory;
use WMC\Symfony\AclBundle\Model\AclSecurityIdentityFactoryInterface as SecurityIdentityFactory;
use WMC\Symfony\AclBundle\Model\AclSecurityIdentityInterface as SecurityIdentity;

/**
 * Default Doctrine ACE provider
 *
 * @author Mathieu Lemoine <mathieu@wemakecustom.com>
 */
class MutableProvider extends AbstractAclProvider implements AclMutableProviderInterface
{
    /**
     * @var ObjectManager
     */
    protected $om;

    /**
     * @var ObjectRepository
     */
    protected $repository;

    /**
     * @var array
     */
    protected $writeCache;

    public function __construct(SecurityIdentityFactory $securityIdentityFactory, TargetIdentityFactory $targetIdentityFactory,
                                ObjectManager $manager, $aclEntryClassname)
    {
        parent::__construct($securityIdentityFactory, $targetIdentityFactory);

        $this->writeCache = new \SplObjectStorage;
        $this->om = $manager;
        $this->repository = $manager->getRepository($aclEntryClassname);
    }

    /**
     * @inheritDoc
     *
     * This will look for already persisted entries only.
     */
    public function findAcl($grantee, $target)
    {
        $grantee = $this->extractSecurityIdentity($grantee);
        $target = $this->extractTargetIdentity($target);

        if (null === $target->getId()
            || (null === $grantee->getId()
                && SecurityIdentity::KIND_ANONYMOUS !== $grantee->getKind())) {
            // Either the target or the (non-anonymous) grantee is not persisted
            // yet. We are sure the database will not return any results.
            return array();
        }

        return $this->repository->findBy(array(
                                               'grantee' => $this->generateCriteriaValue($grantee->getId()),
                                               'target' => $this->generateCriteriaValue($target->getId()),
                                               ));
    }

    /**
     * There is no specification for
     * Doctrine\Common\Persistence\ObjectRepository#findBy()'s $criteria
     * argument structure.
     *
     * This method is provided to allow easy implementation of providers for
     * various Doctrine implementations.
     */
    protected function generateCriteriaValue($value)
    {
        return $value;
    }

    /**
     * @inheritDoc
     *
     * This will look for already persisted entries only.
     */
    public function searchAces(array $grantees = null, array $targets = null, array $permissions = null)
    {
        $criteria = array();

        if (null !== $grantees) {
            $grantees = array_map(array($this, 'extractSecurityIdentity'), $grantees);

            // Filter out non persisted non-anonymous grantee
            $grantees = array_filter($grantees, function(SecurityIdentity $grantee) { return null !== $grantee->getId() || SecurityIdentity::KIND_ANONYMOUS === $grantee->getKind(); });

            $grantees = array_unique(array_map(function(SecurityIdentity $grantee) { return $grantee->getId(); }, $grantees), true);

            if (0 === count($grantees)) {
                return array();
            }

            $criteria['grantee'] = $this->generateCriteriaValue($grantees);
        }

        if (null !== $targets) {
            $targets = array_map(array($this, 'extractTargetIdentity'), $targets);
            $targets = array_map(function(TargetIdentity $target) { return $target->getId(); });

            // Filter out non persisted targets
            $targets = array_unique(array_filter($targets));

            if (0 === count($targets)) {
                return array();
            }

            $criteria['target'] = $this->generateCriteriaValue($targets);
        }

        if (null !== $permissions) {
            if (0 === count($permissions)) {
                return array();
            }

            $criteria['permission'] = $this->generateCriteriaValue($permissions);
        }

        return $this->repository->findBy($criteria);
    }

    /**
     * Inserts a new ACE
     *
     * This method is idempotent.
     */
    public function createAce($grantee, $target, $permission, $flush = true)
    {
        $grantee = $this->extractSecurityIdentity($grantee);
        $target = $this->extractTargetIdentity($target);

        if (!isset($this->writeCache[$grantee])) {
            $this->writeCache[$grantee] = new \SplObjectStorage;
        }

        if (!isset($this->writeCache[$grantee][$target])) {
            // Permissions are strings, no need for an SplObjectStorage.
            $this->writeCache[$grantee][$target] = array();
        }

        $ace = null;

        if (isset($this->writeCache[$grantee][$target][$permission])) {
            $ace = $this->writeCache[$grantee][$target][$permission];
        } else {
            if (null !== $target->getId()
                && (null !== $grantee->getId()
                    || SecurityIdentity::KIND_ANONYMOUS === $grantee->getKind())) {
                // Both the target is persisted and the grantee is either
                // persisted or anonymous. Let's see what the database has to
                // say regarding this ACE.
                $ace = $this->repository->findOneBy(array(
                                                          'grantee'    => $this->generateCriteriaValue($grantee->getId()),
                                                          'target'     => $this->generateCriteriaValue($target->getId()),
                                                          'permission' => $this->generateCriteriaValue($permission),
                                                          ));
            }

            if (null === $ace) {
                $aceClass = $this->repository->getClassName();
                $ace = $this->writeCache[$grantee][$target][$permission] = new $class($grantee, $target, $permission);
                $this->om->persist($ace);
            }
        }

        if ($flush) {
            $this->om->flush();
        }

        return $ace;
    }

    /**
     * Deletes an ACE
     *
     * This method is idempotent.
     */
     public function deleteAce($grantee, $target, $permission, $flush = true)
     {
         $grantee = $this->extractSecurityIdentity($grantee);
         $target = $this->extractTargetIdentity($target);

         $ace = null;

         // Is this ACE in the writeCache?
         if (isset($this->writeCache[$grantee]) && isset($this->writeCache[$grantee][$target]) && isset($this->writeCache[$grantee][$target][$permission])) {
             $ace = $this->writeCache[$grantee][$target][$permission];
         } elseif (null !== $target->getId()
                   && (null !== $grantee->getId()
                       || SecurityIdentity::KIND_ANONYMOUS === $grantee->getKind())) {
             $ace = $this->repository->findOneBy(array(
                                                       'grantee'    => $this->generateCriteriaValue($grantee->getId()),
                                                       'target'     => $this->generateCriteriaValue($target->getId()),
                                                       'permission' => $this->generateCriteriaValue($permission),
                                                       ));
         }

         if (null !== $ace) {
             $this->om->remove($ace);
         }

         if ($flush) {
             $this->om->flush();
         }
     }

     public function clearCache()
     {
         $this->writeCache = new \SplObjectStorage;
     }
}
