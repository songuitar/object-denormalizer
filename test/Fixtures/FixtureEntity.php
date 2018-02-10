<?php

namespace Songuitar\Denormalizer\Test\Fixtures;


class FixtureEntity
{
    /**
     * @var bool
     */
    private $boolField = false;

    /**
     * @var int
     */
    private $intField = 0;

    /**
     * @var float
     */
    private $doubleField = 0.34;

    /**
     * @var \DateTime
     */
    private $dateTimeField;

    /**
     * @var FixtureEmbedEntity[]
     */
    private $collectionField;

    /**
     *
     * @var array
     */
    private $collectionFieldWithDefaultValue = [];

    /**
     * @return bool
     */
    public function isBoolField()
    {
        return $this->boolField;
    }

    /**
     * @param bool $boolField
     *
     * @return FixtureEntity
     */
    public function setBoolField(bool $boolField): self
    {
        $this->boolField = $boolField;

        return $this;
    }

    /**
     * @return int
     */
    public function getIntField()
    {
        return $this->intField;
    }

    /**
     * @param int $intField
     *
     * @return FixtureEntity
     */
    public function setIntField(int $intField): self
    {
        $this->intField = $intField;

        return $this;
    }

    /**
     * @return float
     */
    public function getDoubleField()
    {
        return $this->doubleField;
    }

    /**
     * @param float $doubleField
     *
     * @return FixtureEntity
     */
    public function setDoubleField(float $doubleField): self
    {
        $this->doubleField = $doubleField;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateTimeField()
    {
        return $this->dateTimeField;
    }

    /**
     * @param \DateTime $dateTimeField
     *
     * @return FixtureEntity
     */
    public function setDateTimeField(\DateTime $dateTimeField): self
    {
        $this->dateTimeField = $dateTimeField;

        return $this;
    }

    /**
     * @return array
     */
    public function getCollectionField()
    {
        return $this->collectionField;
    }

    /**
     * @param array $collectionField
     *
     * @return FixtureEntity
     */
    public function setCollectionField($collectionField)
    {
        $this->collectionField = $collectionField;

        return $this;
    }

    /**
     * @return array
     */
    public function getCollectionFieldWithDefaultValue()
    {
        return $this->collectionFieldWithDefaultValue;
    }

    /**
     * @param array $collectionFieldWithDefaultValue
     *
     * @return FixtureEntity
     */
    public function setCollectionFieldWithDefaultValue($collectionFieldWithDefaultValue)
    {
        $this->collectionFieldWithDefaultValue = $collectionFieldWithDefaultValue;

        return $this;
    }
}
