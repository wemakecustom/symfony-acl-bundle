<?php

namespace WMC\Symfony\AclBundle\Domain;

use WMC\Symfony\AclBundle\Model\AclTargetIdentityInterface;
use WMC\Symfony\AclBundle\Model\AclTargetObjectInterface;

use Symfony\Component\Security\Core\Util\ClassUtils;

use WMC\Symfony\AclBundle\Exception\InvalidAclTargetObjectException;

class AclClassFieldTargetIdentity extends AclClassTargetIdentity implements AclTargetIdentityInterface
{
    private static $instances = array();

    /**
     * Expects a class name and a field name
     */
    public static function getInstance($class_name, $field_name)
    {
        if (!is_string($class_name) || !is_string($field_name)) {
            $e = new InvalidAclTargetObjectException('Class name and field name must be a string.');
            $e->setTargetObject($object);
            throw $e;
        }

        $class_name = ClassUtils::getRealClass($class_name);

        return !isset(static::$instances[$class_name][$field_name])
            ? static::$instances[$class_name][$field_name] = new static($class_name, $field_name)
            : static::$instances[$class_name][$field_name];
    }

    protected $field_name;

    protected function __construct($class_name, $field_name)
    {
        parent::__construct($class_name);

        $this->field_name = $field_name;
    }

    public function getFieldName()
    {
        return $this->field_name;
    }
}