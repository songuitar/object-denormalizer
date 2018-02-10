<?php

namespace Songuitar\Denormalizer\Test;

use PHPUnit\Framework\TestCase;
use Songuitar\Common\ReflectionAccessor;
use Songuitar\Denormalizer\Common\ObjectModifierInterface;
use Songuitar\Denormalizer\Denormalizer;
use Songuitar\Denormalizer\Entity\DenormalizationBaseSettings;
use Songuitar\Denormalizer\Exception\DenormalizationException;
use Songuitar\Denormalizer\Service\NormalizedDataStructureResolver;
use Songuitar\Denormalizer\Service\PropertySimpleInfoExtractor;
use Songuitar\Denormalizer\Service\StringValueConverter;
use Songuitar\Denormalizer\Test\Fixtures\FixtureEmbedEntity;
use Songuitar\Denormalizer\Test\Fixtures\FixtureEntity;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;


class DenormalizerTest extends TestCase
{
    /**
     * @var Denormalizer
     */
    private $denormalizer;

    public function setUp()
    {
        parent::setUp();

        $phpDocExtractor = new PhpDocExtractor();
        $propertySimpleInfoExtractor = new PropertySimpleInfoExtractor($phpDocExtractor);

        $this->denormalizer = new Denormalizer(
            $propertySimpleInfoExtractor,
            PropertyAccess::createPropertyAccessor(),
            new ReflectionAccessor(),
            new NormalizedDataStructureResolver(),
            new CamelCaseToSnakeCaseNameConverter(),
            new StringValueConverter($propertySimpleInfoExtractor)
        );
        $this->denormalizer->setBaseSettings(new DenormalizationBaseSettings());
    }

    public function testDenormalize()
    {
        /** @var FixtureEntity $object */
        $object = $this->denormalizer->denormalize(
            [
                'intField' => 1234,
                'boolField' => false,
                'doubleField' => 0.2342100,
                'dateTimeField' => null,
                'collectionField' => [
                    [
                        'intField' => 1,
                        'stringField' => 'text'
                    ],
                    [
                        'intField' => 2,
                        'stringField' => 'text2'
                    ]
                ],
            ],
            FixtureEntity::class
        );

        $this->assertInstanceOf(FixtureEntity::class, $object);

        $this->assertEquals(null, $object->getDateTimeField());
        $this->assertEquals(0.2342100, $object->getDoubleField());
        $this->assertEquals(1234, $object->getIntField());
        $this->assertEquals([
            (new FixtureEmbedEntity())
                ->setIntField(1)
                ->setStringField('text'),
            (new FixtureEmbedEntity())
                ->setIntField(2)
                ->setStringField('text2'),
        ],
            $object->getCollectionField()
        );
    }

    public function testDenormalizeAndModify()
    {
        $modifier = new class implements ObjectModifierInterface {
            /**
             * @param FixtureEmbedEntity $object
             * @return void
             */
            public function modify($object): void
            {
                $object->setStringField('modified');
            }

            /**
             * @return string
             */
            public function getSubjectClass(): string
            {
                return FixtureEmbedEntity::class;
            }
        };

        $this->denormalizer->addObjectModifier($modifier);

        /** @var FixtureEntity $object */
        $object = $this->denormalizer->denormalize(
            [
                'intField' => 1234,
                'boolField' => false,
                'doubleField' => 0.2342100,
                'dateTimeField' => null,
                'collectionField' => [
                    [
                        'intField' => 1,
                        'stringField' => 'text'
                    ],
                    [
                        'intField' => 2,
                        'stringField' => 'text2'
                    ]
                ],
            ],
            FixtureEntity::class
        );

        $this->assertInstanceOf(FixtureEntity::class, $object);

        $this->assertEquals(null, $object->getDateTimeField());
        $this->assertEquals(0.2342100, $object->getDoubleField());
        $this->assertEquals(1234, $object->getIntField());
        $this->assertEquals([
            (new FixtureEmbedEntity())
                ->setIntField(1)
                ->setStringField('modified'),
            (new FixtureEmbedEntity())
                ->setIntField(2)
                ->setStringField('modified'),
        ],
            $object->getCollectionField()
        );
    }

    public function testDenormalizeWithWrongTypes()
    {
        /** @var FixtureEntity $object */
        $object = $this->denormalizer->denormalize(
            [
                'intField' => ' 1234 ',
                'boolField' => '  ',
                'doubleField' => '  0.2342100  ',
                'dateTimeField' => ' ',
            ],
            FixtureEntity::class
        );

        $this->assertInstanceOf(FixtureEntity::class, $object);

        $this->assertEquals(null, $object->getDateTimeField());
        $this->assertEquals(0.2342100, $object->getDoubleField());
        $this->assertEquals(1234, $object->getIntField());
    }

    public function testIgnoreExtraAttributesContext()
    {
        /** @var FixtureEntity $object */
        $object = $this->denormalizer->denormalize(
            [
                'intField' => 1234,
                'boolField' => false,
                'doubleField' => 0.2342100,
                //will be ignored
                'non-existing-field' => ' ',
            ],
            FixtureEntity::class,
            null,
            [
                'skip_extra_attributes' => true,
            ]
        );
        $this->assertInstanceOf(FixtureEntity::class, $object);

        $this->assertEquals(null, $object->getDateTimeField());
    }

}
