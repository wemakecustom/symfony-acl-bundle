<?php

namespace WMC\Symfony\AclBundle\Domain;

use WMC\Symfony\AclBundle\Model\AclTargetIdentityInterface;
use WMC\Symfony\AclBundle\Model\AclTargetObjectInterface;

use Symfony\Component\Security\Core\Util\ClassUtils;

use WMC\Symfony\AclBundle\Exception\InvalidAclTargetObjectException;

class AclClassTargetIdentity implements AclTargetIdentityInterface
{
    private static $instances = array();

    /**
     * Expects a class name
     */
    public static function getInstance($class_name)
    {
        if (!is_string($class_name)) {
            $e = new InvalidAclTargetObjectException('Class name must be a string.');
            $e->setTargetObject($object);
            throw $e;
        }

        $class_name = ClassUtils::getRealClass($class_name);

        return !isset(static::$instances[$class_name])
            ? static::$instances[$class_name] = new self($class_name)
            : static::$instances[$class_name];
    }

    protected $class_name;

    protected function __construct($class_name)
    {
        $this->class_name = $class_name;
    }

    public function getClassName()
    {
        return $this->class_name;
    }

    public function getObjectIdentifier()
    {
        return null;
    }

    public function getFieldName()
    {
        return null;
    }

    public function equals(AclTargetIdentityInterface $identity)
    {
        return $identity instanceof AclTargetIdentityInterface
            && $identity->getClassName() === $identity->getClassName()
            && $identity->getObjectIdentifier() === $this->getObjectIdentifier()
            && $identity->getFieldName();
    }
}