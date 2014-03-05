<?php

namespace WMC\Symfony\AclBundle\Provider\Doctrine;

class ClassFieldTargetIdentity extends AbstractTargetIdentity
{
    /**
     * @var ClassTargetIdentity
     */
    protected $class;

    public function __construct(ClassTargetIdentity $class, $fieldName)
    {
        parent::__construct($class->getClassName(), null, $fieldName);

        $this->class = $class;
    }

    public function getClassIdentity()
    {
        return $this->class;
    }
}