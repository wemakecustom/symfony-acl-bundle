<?php

namespace WMC\Symfony\AclBundle\Provider\Doctrine;

use WMC\Symfony\AclBundle\Domain\AclTargetIdentityFactory as BaseFactory;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;

use Doctrine\Common\Util\ClassUtils;

class TargetIdentityFactory extends BaseFactory
{
    /**
     * @var ObjectRepository
     */
    protected $classRepository;

    /**
     * @var array
     */
    protected $classCache = array();

    /**
     * @var ObjectRepository
     */
    protected $classFieldRepository;

    /**
     * @var array
     */
    protected $classFieldCache = array();

    /**
     * @var ObjectRepository
     */
    protected $objectRepository;

    /**
     * @var array
     */
    protected $objectCache = array();

    /**
     * @var ObjectRepository
     */
    protected $objectFieldRepository;

    /**
     * @var array
     */
    protected $objectFieldCache = array();

    public function __construct(ObjectManager $manager, $classClassname, $objectClassname, $classFieldClassname, $objectFieldClassname)
    {
        $this->classRepository      = $manager->getRepository($classClassname);
        $this->classFieldRepository = $manager->getRepository($classFieldClassname);

        $this->objectRepository      = $manager->getRepository($objectClassname);
        $this->objectFieldRepository = $manager->getRepository($objectFieldClassname);
    }

    public function createClassIdentity($className)
    {
        $className = ClassUtils::getRealClass($className);

        if (isset($this->classCache[$className])) {
            return $this->classCache[$className];
        }

        if (null !== ($this->classCache[$className] = $this->classRepository->findOneByName($className))) {
            return $this->classCache[$className];
        }

        $classClass = $this->classRepository->getClassName();
        return $this->classCache[$className] = new $classClass($className);
    }

    public function createClassFieldIdentity($className, $fieldName)
    {
        $class = $this->createClassIdentity($className);

        if (null === $fieldName) {
            return $class;
        }

        if (isset($this->classFieldCache[$class->getClassName()][$fieldName])) {
            return $this->classFieldCache[$class->getClassName()][$fieldName];
        }

        if (null !== $class->getId()
            && null !== ($this->classFieldCache[$class->getClassName()][$fieldName] = $this->classFieldRepository->findOneBy(array('class' => $class->getId(), 'fieldName' => $fieldName)))) {
            return $this->classFieldCache[$class->getClassName()][$fieldName];
        }

        $classFieldClass = $this->classFieldRepository->getClassName();
        return $this->classFieldCache[$class->getClassName()][$fieldName] = new $classFieldClass($class, $fieldName);
    }

    public function createObjectIdentity($object)
    {
        list($className, $identifier) = $this->extractObjectIdentityFields($object);

        if (isset($this->objectCache[$className][$identifier])) {
            return $this->objectCache[$className][$identifier];
        }


        $class = $this->createClassIdentity($className);

        if (null !== $class->getId()
            && null !== ($this->objectCache[$className][$identifier] = $this->objectRepository->findOneBy(array('class' => $class->getId(), 'objectId' => $identifier)))) {
            return $this->objectCache[$className][$identifier];
        }

        $objectClass = $this->objectRepository->getClassName();
        return $this->objectCache[$className][$identifier] = new $objectClass($class, $identifier);
    }

    public function createObjectFieldIdentity($object, $fieldName)
    {
        list($className, $identifier) = $this->extractObjectIdentityFields($object);

        if (isset($this->objectFieldCache[$className][$identifier][$fieldName])) {
            return $this->objectFieldCache[$className][$identifier][$fieldName];
        }

        if (null !== $object->getId()
            && null !== ($this->objectFieldCache[$className][$identifier][$fieldName] = $this->objectFieldRepository->findOneBy(array('object' => $object->getId(), 'fieldName' => $fieldName)))) {
            return $this->objectFieldCache[$className][$identifier][$fieldName];
        }

        $objectFieldClass = $this->objectFieldRepository->getClassName();
        return $this->objectFieldCache[$className][$identifier][$fieldName] = new $objectFieldClass($object, $fieldName, $this->createClassFieldIdentity($object->getClassName(), $field));
    }

    public function clearCache()
    {
        $this->classCache       = array();
        $this->classFieldCache  = array();
        $this->objectCache      = array();
        $this->objectFieldCache = array();
    }
}