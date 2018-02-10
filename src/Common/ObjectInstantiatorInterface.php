<?php

namespace Songuitar\Denormalizer\Common;


interface ObjectInstantiatorInterface
{
    /**
     * Method to instantiate objects
     *
     * @param mixed $data
     * @param string $class
     * @return mixed
     */
    public function instantiate($data, string $class);

    /**
     * @param mixed $data
     * @param string $class
     * @return bool
     */
    public function isSupportsInstantiation($data, string $class) : bool;
}