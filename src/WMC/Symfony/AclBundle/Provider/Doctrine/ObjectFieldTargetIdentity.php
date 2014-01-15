<?php

namespace WMC\Symfony\Provider\Doctrine;

class ObjectFieldTargetIdentity extends AbstractTargetIdentity
{
    /**
     * @var ObjectTargetIdentity
     */
    protected $object;

    /**
     * @var ClassFieldTargetIdentity
     */
    protected $classField;

    public function __construct(ObjectTargetIdentity $object, $fieldName, ClassFieldTargetIdentity $classField)
    {
        parent::__construct($object->getClassName(), $object->getObjectIdentifier(), $fieldName);

        $this->object = $object;

        if ($this->getClassName() !== $classField->getClassName()
            || $this->getFieldName() !== $classField->getFieldName()) {
            throw new \InvalidArgumentException('The objectField specification given ['.$this->getClassName().','.$this->getObjectIdentifier().','.$this->getFieldName().'] is not compatible with the given classField identity ['.$classField->getClassName().', '.$classField->getFieldName().'].');
        }

        $this->classField = $classField;
    }

    public function getObjectIdentity()
    {
        return $this->object;
    }

    public function getClassFieldIdentity()
    {
        return $this->classField;
    }
}