<?php

namespace Songuitar\Denormalizer;

use Songuitar\Common\ReflectionAccessorInterface;
use Songuitar\Denormalizer\Common\DenormalizationBaseSettingsInterface;
use Songuitar\Denormalizer\Common\DenormalizationContextualSettingsInterface;
use Songuitar\Denormalizer\Entity\PropertyInfo;
use Songuitar\Denormalizer\Common\ObjectModifierInterface;
use Songuitar\Denormalizer\Entity\DenormalizationContextualSettings;
use Songuitar\Denormalizer\Exception\DenormalizationException;
use Songuitar\Denormalizer\Service\NormalizedDataStructureResolverInterface;
use Songuitar\Denormalizer\Service\PropertySimpleInfoExtractorInterface;
use Songuitar\Denormalizer\Service\StringValueConverterInterface;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * Class Denormalizer.
 */
class Denormalizer implements DenormalizerInterface
{
    /**
     * @var PropertySimpleInfoExtractorInterface
     */
    private $propertyInfoExtractor;

    /**
     * @var PropertyAccessorInterface
     */
    private $basePropertyAccessor;

    /**
     * @var ReflectionAccessorInterface
     */
    private $reflectionPropertyAccessor;

    /**
     * @var NormalizedDataStructureResolverInterface
     */
    private $typeResolver;

    /**
     * @var NameConverterInterface
     */
    private $nameConverter;

    /**
     * @var StringValueConverterInterface
     */
    private $stringValueConverter;

    /**
     * @var ObjectModifierInterface[]
     */
    private $objectModifiers = [];

    /**
     * @var DenormalizationBaseSettingsInterface
     */
    private $baseSettings;

    public function __construct(
        PropertySimpleInfoExtractorInterface $propertyInfoExtractor,
        PropertyAccessorInterface $propertyAccessor,
        ReflectionAccessorInterface $reflectionAccessor,
        NormalizedDataStructureResolverInterface $typeResolver,
        NameConverterInterface $nameConverter = null,
        StringValueConverterInterface $stringValueConverter = null
    )
    {
        $this->propertyInfoExtractor = $propertyInfoExtractor;
        $this->typeResolver = $typeResolver;
        $this->basePropertyAccessor = $propertyAccessor;
        $this->reflectionPropertyAccessor = $reflectionAccessor;
        $this->nameConverter = $nameConverter;
        $this->stringValueConverter = $stringValueConverter;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \UnexpectedValueException
     * @throws DenormalizationException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        $this->prepareSettings($context);
        return $this->denormalizeRecursively($data, $class, new DenormalizationContextualSettings());
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return is_array($data) && class_exists($type);
    }

    /**
     * @param $data
     * @param $class
     * @param DenormalizationContextualSettingsInterface $context
     *
     * @throws \InvalidArgumentException
     * @throws DenormalizationException
     * @throws \UnexpectedValueException
     * @throws \LogicException
     *
     * @return null|array|object
     */
    public function denormalizeRecursively($data, string $class, DenormalizationContextualSettingsInterface $context)
    {
        $type = $this->typeResolver->resolve($data);

        //TODO: implement object instantiator logic instead
        switch ($type) {
            case NormalizedDataStructureResolverInterface::TYPE_IDENTIFIER_MULTIPLE:

                break;
            case NormalizedDataStructureResolverInterface::TYPE_EMPTY:

                return $this->commonEmptyObjectHandler($class, $context);
                break;
            case NormalizedDataStructureResolverInterface::TYPE_NORMALIZED_OBJECT:
                return $this->denormalizeObject($data, $class, $context);
                break;
            case NormalizedDataStructureResolverInterface::TYPE_NORMALIZED_OBJECT_MULTIPLE:
                foreach ($data as &$elem) {
                    $elem = $this->denormalizeObject($elem, $class, $context);
                }

                return $data;
                break;
        }

        throw new \InvalidArgumentException('unexpected data structure');
    }


    /**
     * @return DenormalizationBaseSettingsInterface
     */
    public function getBaseSettings(): DenormalizationBaseSettingsInterface
    {
        return $this->baseSettings;
    }

    /**
     * @param DenormalizationBaseSettingsInterface $baseSettings
     *
     * @return Denormalizer
     */
    public function setBaseSettings(DenormalizationBaseSettingsInterface $baseSettings): self
    {
        $this->baseSettings = $baseSettings;

        return $this;
    }

    /**
     * @param string $class
     * @param DenormalizationContextualSettingsInterface $context
     *
     * @return object | array
     */
    protected function commonEmptyObjectHandler(string $class, DenormalizationContextualSettingsInterface $context)
    {
        /** @var PropertyInfo $propertyInfo */
        if ($propertyInfo = $context->getEmbedObjectInfo()) {
            if ($propertyInfo->isCollection()) {
                return [];
            }
        }

        return $this->applyObjectModifiers(new $class());
    }

    /**
     * @param array $data
     * @param string $class
     *
     * @return null|object
     */
    protected function getObject(array $data, string $class)
    {
        //TODO: implement instantiator logic instead
        return new $class();
    }


    /**
     * @param object $object
     *
     * @throws \LogicException
     *
     * @return object
     */
    protected function applyObjectModifiers($object)
    {
        $baseModifier = null;
        $specialModifier = null;

        foreach ($this->getObjectModifiers() as $modifier) {

            $reflection = new \ReflectionClass($object);

            if ($reflection->isSubclassOf($modifier->getSubjectClass())) {
                $baseModifier = $modifier;
            } elseif ($reflection->getName() === $modifier->getSubjectClass()) {
                $specialModifier = $modifier;
            }
        }

        if (null !== $baseModifier) {
            $baseModifier
                ->modify($object);
        }

        if (null !== $specialModifier) {
            $specialModifier
                ->modify($object);
        }

        return $object;
    }

    /**
     * @param ObjectModifierInterface $objectModifier
     *
     * @return $this
     */
    public function addObjectModifier(ObjectModifierInterface $objectModifier)
    {
        $this->objectModifiers[] = $objectModifier;
        return $this;
    }

    /**
     * @return ObjectModifierInterface[]
     */
    protected function getObjectModifiers()
    {
        return $this->objectModifiers;
    }

    /**
     * @param array $context
     *
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     * @throws DenormalizationException
     *
     * @return null|array|DenormalizationContextualSettingsInterface|object
     */
    private function prepareSettings(array $context = [])
    {
        $this->getBaseSettings()->setSkipExtraAttributes(true);

        /** @var DenormalizationBaseSettingsInterface $baseSettings */
        $baseSettings = $this->denormalizeRecursively(
            $context,
            get_class($this->getBaseSettings()),
            new DenormalizationContextualSettings()
        );

        $this->setBaseSettings($baseSettings);

    }

    /**
     * @param array $data
     * @param string $class
     * @param DenormalizationContextualSettingsInterface $context
     *
     * @throws \LogicException
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     * @throws DenormalizationException
     *
     * @return null|object
     */
    private function denormalizeObject(array $data, string $class, DenormalizationContextualSettingsInterface $context)
    {
        $object = $this->getObject($data, $class);

        foreach ($data as $propertyName => $elem) {

            if ($this->nameConverter !== null) {
                $propertyName = $this->nameConverter->denormalize($propertyName);
            }

            $propertyInfo = $this->propertyInfoExtractor->getInfo($class, $propertyName);

            if ($propertyInfo !== null) {

                if (is_string($elem) && $this->getBaseSettings()->isEnableAutoCasting()) {
                    $elem = $this->stringValueConverter->convert($elem, $propertyName, $class);
                }

                if ($propertyInfo->getClass()) {

                    //TODO: implement object instantiator logic instead
                    /*                if (is_int($elem) && $propertyInfo->isDoctrine()) {
                                        $elem = $this->convertSingleIdentifier($elem, $propertyInfo->getClass(), $context);
                                    }*/


                    if (is_array($elem)) {
                        $elem = $this->denormalizeRecursively(
                            $elem,
                            $propertyInfo->getClass(),
                            $context
                                ->setEmbedObjectInfo($propertyInfo)
                                ->setParentProperty($propertyName)
                                ->setParentClass($class)
                        );
                    }
                    if (is_string($elem) && \DateTime::class === $propertyInfo->getClass()) {
                        $elem = new \DateTime($elem);
                    }
                }
            }

            $this->writeObjectProperty($object, $propertyName, $elem);
        }

        return $this->applyObjectModifiers($object);
    }

    /**
     * @param object $object
     * @param string $propertyName
     * @param mixed $value
     * @throws DenormalizationException
     */
    private function writeObjectProperty($object, string $propertyName, $value): void
    {
        try {
            if (
                null === $value
                || $value instanceof \stdClass
                || $this->getBaseSettings()->isAccessByReflection()
                || in_array($propertyName, $this->getBaseSettings()->getAccessByReflectionList(), true)
            ) {
                $this->reflectionPropertyAccessor->setValue($object, $propertyName, $value);
            } else {
                $this->basePropertyAccessor->setValue($object, $propertyName, $value);
            }
        } catch (NoSuchPropertyException $exception) {
            $this->handleNonExistingProperty($propertyName);
        }
    }

    /**
     * @param string $propertyName
     * @throws DenormalizationException
     */
    private function handleNonExistingProperty(string $propertyName): void
    {
        if ($this->getBaseSettings()->isSkipExtraAttributes()) {
            return;
        }

        throw new DenormalizationException(
            'no such property',
            [$propertyName]
        );
    }
}
