<?php

namespace Songuitar\Denormalizer\Common;


interface ObjectModifierInterface
{
    /**
     * @param object $object
     * @return void
     */
    public function modify($object) : void;

    /**
     * @return string
     */
    public function getSubjectClass() : string;
}