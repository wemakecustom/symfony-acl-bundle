<?php

namespace WMC\Symfony\AclBundle\Model;

interface AclTargetIdentityFactoryInterface
{
    /**
     * Convert a domain object (potentiel target) to a
     * AclTargetObjectInterface
     *
     * Accepts (not processed in this order):
     *  - string (Class name)
     *  - Object
     *  - [Object, string (Field name)]
     *  - [string (Class name), string (Field name)]
     *  - AclTargetObjectInterface (itself)
     */
    public function extractTargetIdentity($target);

    public function createClassIdentity($className);

    public function createClassFieldIdentity($className, $fieldName);

    /**
     * $object can also be specified as an array:
     * [string (Class name), string (identifier)]
     */
    public function createObjectIdentity($object);

    /**
     * $object can also be specified as an array:
     * [string (Class name), string (identifier)]
     */
    public function createObjectFieldIdentity($object, $fieldName);
}