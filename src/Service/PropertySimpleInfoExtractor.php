<?php

namespace Songuitar\Denormalizer\Service;


use Songuitar\Denormalizer\Common\PropertyInfoInterface;
use Songuitar\Denormalizer\Entity\PropertyInfo;
use Songuitar\Denormalizer\Exception\TypeResolvingException;
use Symfony\Component\PropertyInfo\PropertyTypeExtractorInterface;
use Symfony\Component\PropertyInfo\Type;

class PropertySimpleInfoExtractor implements PropertySimpleInfoExtractorInterface
{
    /**
     * @var PropertyTypeExtractorInterface
     */
    private $typeExtractor;

    /**
     * PropertyInfoExtractor constructor.
     * @param PropertyTypeExtractorInterface $typeExtractor
     */
    public function __construct(PropertyTypeExtractorInterface $typeExtractor)
    {
        $this->typeExtractor = $typeExtractor;
    }

    /**
     * @param string $class
     * @param string $propertyName
     *
     * @return PropertyInfoInterface
     *
     * @throws TypeResolvingException
     */
    public function getInfo(string $class, string $propertyName): ? PropertyInfoInterface
    {
        $propertyInfo = new PropertyInfo();

        if ($types = $this->typeExtractor->getTypes($class, $propertyName)) {
            /** @var Type[] $types */
            foreach ($types as $type) {
                if ($type->isCollection() && $type->getCollectionValueType()) {
                    if ($type->getCollectionValueType()->getClassName()) {
                        return $propertyInfo
                            ->setClass($type->getCollectionValueType()->getClassName())
                            ->setCollection(true);
                    }
                }
                if (Type::BUILTIN_TYPE_OBJECT === $type->getBuiltinType()) {
                    return $propertyInfo
                        ->setBuiltinType(Type::BUILTIN_TYPE_OBJECT)
                        ->setClass($type->getClassName());
                }

                if ($type->getBuiltinType() !== Type::BUILTIN_TYPE_NULL) {
                    return $propertyInfo->setBuiltinType($type->getBuiltinType());
                }
            }

            throw new TypeResolvingException('can\'t determine certain type for ' . $class . '::$' . $propertyName);
        }

        return null;
    }
}