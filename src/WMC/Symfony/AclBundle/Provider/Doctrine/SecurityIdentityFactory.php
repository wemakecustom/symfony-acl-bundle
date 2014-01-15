<?php

namespace WMC\Symfony\AclBundle\Provider\Doctrine;

use WMC\Symfony\AclBundle\Domain\AclSecurityIdentityFactory as BaseFactory;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;

use Doctrine\Common\Util\ClassUtils;

class SecurityIdentityFactory extends BaseFactory
{
    /**
     * @var ObjectRepository
     */
    protected $roleRepository;

    /**
     * @var array
     */
    protected $roleCache = array();

    /**
     * @var ObjectRepository
     */
    protected $userRepository;

    /**
     * @var array
     */
    protected $userCache = array();

    public function __construct(ObjectManager $manager, $roleClassname, $userClassname)
    {
        $this->roleRepository = $manager->getRepository($roleClassname);
        $this->userRepository = $manager->getRepository($userClassname);
    }

    public function createAnonymousIdentity()
    {
        if (null === $this->anonymousIdentity) {
            $this->anonymousIdentity = new AnonymousSecurityIdentity;
        }

        return $this->anonymousIdentity;
    }

    public function createRoleIdentity($role)
    {
        list($className, $identifier) = $this->extractRoleIdentityFields($role);

        if (isset($this->roleCache[$className][$identifier])) {
            return $this->roleCache[$className][$identifier];
        }

        if (null !== ($this->roleCache[$className][$identifier] = $this->roleRepository->findOneBy(array('class' => $className, 'identifier' => $identifier)))) {
            return $this->roleCache[$className][$identifier];
        }

        $roleClass = $this->roleRepository->getClassName();
        return $this->roleCache[$className][$identifier] = new $roleClass($className, $identifier);
    }

    public function createUserIdentity($user)
    {
        list($className, $identifier) = $this->extractUserIdentityFields($user);

        if (isset($this->userCache[$className][$identifier])) {
            return $this->userCache[$className][$identifier];
        }

        if (null !== ($this->userCache[$className][$identifier] = $this->userRepository->findOneBy(array('class' => $className, 'identifier' => $identifier)))) {
            return $this->userCache[$className][$identifier];
        }

        $userClass = $this->userRepository->getClassName();
        return $this->userCache[$className][$identifier] = new $userClass($className, $identifier);
    }

    public function clearCache()
    {
        $this->roleCache = array();
        $this->userCache = array();
    }
}