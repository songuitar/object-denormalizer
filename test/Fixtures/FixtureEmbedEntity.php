<?php


namespace Songuitar\Denormalizer\Test\Fixtures;


class FixtureEmbedEntity
{
    /**
     * @var int
     */
    private $intField = 0;

    /**
     * @var string
     */
    private $stringField = '';

    /**
     * @return int
     */
    public function getIntField(): int
    {
        return $this->intField;
    }

    /**
     * @param int $intField
     * @return FixtureEmbedEntity
     */
    public function setIntField(int $intField): FixtureEmbedEntity
    {
        $this->intField = $intField;
        return $this;
    }

    /**
     * @return string
     */
    public function getStringField(): string
    {
        return $this->stringField;
    }

    /**
     * @param string $stringField
     * @return FixtureEmbedEntity
     */
    public function setStringField(string $stringField): FixtureEmbedEntity
    {
        $this->stringField = $stringField;
        return $this;
    }
}