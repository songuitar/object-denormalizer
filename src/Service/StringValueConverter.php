<?php

namespace Songuitar\Denormalizer\Service;


use Songuitar\Denormalizer\Exception\StringConversionException;
use Symfony\Component\PropertyInfo\PropertyTypeExtractorInterface;
use Symfony\Component\PropertyInfo\Type;

class StringValueConverter implements StringValueConverterInterface
{

    /**
     * @var PropertySimpleInfoExtractorInterface
     */
    private $propertySimpleInfoExtractor;

    /**
     * StringValueConverter constructor.
     * @param PropertySimpleInfoExtractorInterface $propertySimpleInfoExtractor
     */
    public function __construct(PropertySimpleInfoExtractorInterface $propertySimpleInfoExtractor)
    {
        $this->propertySimpleInfoExtractor = $propertySimpleInfoExtractor;
    }

    /**
     * Casts given string to expected scalar type
     *
     * @param string $value
     * @param string $propertyName
     * @param string $class
     *
     * @return mixed
     *
     * @throws StringConversionException
     */
    public function convert(string $value, string $propertyName, string $class)
    {
        if ($builtInType = $this->propertySimpleInfoExtractor->getInfo($class, $propertyName)->getBuiltinType()) {

                $exception = new StringConversionException(
                    sprintf(
                        'can\'t automatically cast such string to expected type %1$s for attribute %2$s',
                        $builtInType,
                        $propertyName
                    )
                );

                if (in_array(
                    $builtInType,
                    [
                        Type::BUILTIN_TYPE_FLOAT,
                        Type::BUILTIN_TYPE_INT,
                    ],
                    true
                )) {
                    $value = trim($value);
                    if (ctype_digit(str_replace('.', '', $value))) {
                        settype($value, $builtInType);

                        return $value;
                    }

                    throw $exception;
                }
                if (Type::BUILTIN_TYPE_BOOL === $builtInType) {
                    $value = trim($value);
                    if (strlen($value) > 1 || ($value && !ctype_digit($value))) {
                        throw $exception;
                    }
                    $value = (bool) $value;

                    return $value;
                }
                if (Type::BUILTIN_TYPE_OBJECT === $builtInType) {
                    $value = trim($value);
                    if (!trim($value)) {
                        $value = null;
                    }
                    if (ctype_digit($value)) {
                        $value = (int) $value;
                    }
                }
        }

        return $value;
    }
}