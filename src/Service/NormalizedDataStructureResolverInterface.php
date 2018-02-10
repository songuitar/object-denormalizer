<?php

namespace Songuitar\Denormalizer\Service;


interface NormalizedDataStructureResolverInterface
{
    const TYPE_NORMALIZED_OBJECT = 'normalized_object';
    const TYPE_NORMALIZED_OBJECT_MULTIPLE = 'normalized_object_multi';
    const TYPE_IDENTIFIER = 'identifier';
    const TYPE_IDENTIFIER_MULTIPLE = 'identifier_multi';
    const TYPE_MIXED = 'mixed';
    const TYPE_EMPTY = 'empty';
    const TYPE_OTHER_SCALAR = 'other_scalar';
    const IDENTIFIER_BUILTIN_TYPE = 'integer';

    /**
     * @param mixed $data
     * @return string
     */
    public function resolve($data) : string;
}