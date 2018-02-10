<?php

namespace Songuitar\Denormalizer\Common;


interface DenormalizationContextualSettingsInterface
{
    /**
     * @return PropertyInfoInterface
     */
    public function getEmbedObjectInfo(): ? PropertyInfoInterface;

    /**
     * @param PropertyInfoInterface $embedObjectInfo
     * @return $this
     */
    public function setEmbedObjectInfo(PropertyInfoInterface $embedObjectInfo);

    /**
     * @return string
     */
    public function getParentProperty(): string;

    /**
     * @param string $parentProperty
     * @return $this
     */
    public function setParentProperty(string $parentProperty);

    /**
     * @return string
     */
    public function getParentClass(): string;

    /**
     * @param string $parentClass
     * @return $this
     */
    public function setParentClass(string $parentClass);
}