<?php

namespace Songuitar\Denormalizer\Service;


interface StringValueConverterInterface
{
    /**
     * Casts given string to expected scalar type
     *
     * @param string $value
     * @param string $propertyName
     * @param string $class
     * @return mixed
     */
    public function convert(string $value, string $propertyName, string $class);
}