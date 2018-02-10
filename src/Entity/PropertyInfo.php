<?php

namespace Songuitar\Denormalizer\Entity;

use Songuitar\Denormalizer\Common\PropertyInfoInterface;

class PropertyInfo implements PropertyInfoInterface
{
    /**
     * @var string
     */
    private $class;

    /**
     * @var bool
     */
    private $collection = false;

    /**
     * @var
     */
    private $builtinType;

    /**
     * @return string
     */
    public function getClass(): ? string
    {
        return $this->class;
    }

    /**
     * @param string $class
     *
     * @return PropertyInfo
     */
    public function setClass(string $class): self
    {
        $this->class = $class;

        return $this;
    }

    /**
     * @return bool
     */
    public function isCollection(): bool
    {
        return $this->collection;
    }

    /**
     * @param bool $collection
     *
     * @return PropertyInfo
     */
    public function setCollection(bool $collection): self
    {
        $this->collection = $collection;

        return $this;
    }

    /**
     * @return string
     */
    public function getBuiltinType() : string
    {
        return $this->builtinType;
    }

    /**
     * @param string $builtinType
     * @return PropertyInfo
     */
    public function setBuiltinType(string $builtinType)
    {
        $this->builtinType = $builtinType;
        return $this;
    }
}
