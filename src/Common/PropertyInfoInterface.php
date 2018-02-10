<?php

namespace Songuitar\Denormalizer\Common;

interface PropertyInfoInterface
{
    /**
     * @return string
     */
    public function getClass(): ? string;

    /**
     * @return bool
     */
    public function isCollection(): bool;

    /**
     * @return string
     */
    public function getBuiltinType(): string;
}