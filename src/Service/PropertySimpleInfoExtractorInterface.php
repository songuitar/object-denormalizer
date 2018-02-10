<?php

namespace Songuitar\Denormalizer\Service;

use Songuitar\Denormalizer\Common\PropertyInfoInterface;

/**
 * Interface PropertyInfoExtractorInterface
 * @package Songuitar\Denormalizer\Service
 *
 * Get single object with information about property type (encapsulates work with \Symfony\Component\PropertyInfo\PropertyTypeExtractorInterface)
 */
interface PropertySimpleInfoExtractorInterface
{
    /**
     * @param string $class
     * @param string $propertyName
     * @return PropertyInfoInterface
     */
    public function getInfo(string $class, string $propertyName) : ? PropertyInfoInterface;
}