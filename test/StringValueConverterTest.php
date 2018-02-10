<?php

namespace Songuitar\Denormalizer\Test;

use PHPUnit\Framework\TestCase;
use Songuitar\Denormalizer\Exception\StringConversionException;
use Songuitar\Denormalizer\Service\PropertySimpleInfoExtractor;
use Songuitar\Denormalizer\Service\StringValueConverter;
use Songuitar\Denormalizer\Test\Fixtures\FixtureEntity;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;

class StringValueConverterTest extends TestCase
{
    public function testConvert()
    {
        $converter = new StringValueConverter(new PropertySimpleInfoExtractor(new PhpDocExtractor()));

        $value = $converter->convert( ' 1234 ','intField', FixtureEntity::class);

        $this->assertEquals(1234, $value);

        $value = $converter->convert(
            '  ',
            'boolField',
            FixtureEntity::class
        );
        $this->assertEquals(false, $value);

        $value = $converter->convert(
            '0  ',
            'boolField',
            FixtureEntity::class
        );
        $this->assertEquals(false, $value);

        $value = $converter->convert(
            ' 0.2342100 ',
            'doubleField',
            FixtureEntity::class
        );
        $this->assertEquals(0.2342100, $value);

        $value = $converter->convert(
            ' ',
            'dateTimeField',
            FixtureEntity::class
        );
        $this->assertEquals(null, $value);

        $this->expectException(StringConversionException::class);
        $value = $converter->convert(
            '  06 ',
            'boolField',
            FixtureEntity::class
        );
        $this->assertEquals(false, $value);
    }
}