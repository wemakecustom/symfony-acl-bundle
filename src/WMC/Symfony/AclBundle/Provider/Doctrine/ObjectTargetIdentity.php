<?php

namespace WMC\Symfony\Provider\Doctrine;

class ObjectTargetIdentity extends AbstractTargetIdentity
{
    /**
     * @var ClassTargetIdentity
     */
    protected $class;

    public function __construct(ClassTargetIdentity $class, $objectId)
    {
        parent::__construct($class->getClassName(), $objectId);

        $this->class = $class;
    }

    public function getClassIdentity()
    {
        return $this->class;
    }
}