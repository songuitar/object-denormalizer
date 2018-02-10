<?php

namespace Songuitar\Denormalizer\Entity;

use Songuitar\Denormalizer\Common\DenormalizationContextualSettingsInterface;
use Songuitar\Denormalizer\Common\PropertyInfoInterface;

class DenormalizationContextualSettings implements DenormalizationContextualSettingsInterface
{
    /**
     * @var PropertyInfo
     */
    private $embedObjectInfo;

    /**
     * @var string
     */
    private $parentProperty;

    /**
     * @var string
     */
    private $parentClass;

    /**
     * @return PropertyInfoInterface
     */
    public function getEmbedObjectInfo(): ? PropertyInfoInterface
    {
        return $this->embedObjectInfo;
    }

    /**
     * @param PropertyInfoInterface $embedObjectInfo
     * @return DenormalizationContextualSettings
     */
    public function setEmbedObjectInfo(PropertyInfoInterface $embedObjectInfo): DenormalizationContextualSettings
    {
        $this->embedObjectInfo = $embedObjectInfo;
        return $this;
    }

    /**
     * @return string
     */
    public function getParentProperty(): string
    {
        return $this->parentProperty;
    }

    /**
     * @param string $parentProperty
     * @return DenormalizationContextualSettings
     */
    public function setParentProperty(string $parentProperty): DenormalizationContextualSettings
    {
        $this->parentProperty = $parentProperty;
        return $this;
    }

    /**
     * @return string
     */
    public function getParentClass(): string
    {
        return $this->parentClass;
    }

    /**
     * @param string $parentClass
     * @return DenormalizationContextualSettings
     */
    public function setParentClass(string $parentClass): DenormalizationContextualSettings
    {
        $this->parentClass = $parentClass;
        return $this;
    }
}
