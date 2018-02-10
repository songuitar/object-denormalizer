<?php

namespace Songuitar\Denormalizer\Entity;


use Songuitar\Denormalizer\Common\DenormalizationBaseSettingsInterface;

class DenormalizationBaseSettings implements DenormalizationBaseSettingsInterface
{

    /**
     * @var bool
     */
    private $skipExtraAttributes = false;

    /**
     * @var bool
     */
    private $enableAutoCasting = true;

    /**
     * @var bool
     */
    private $createOrUpdate = false;

    /**
     * Names or properties that should be accessed using reflection.
     *
     * @var string[]
     */
    private $accessByReflectionList = [];

    /**
     * @var bool
     */
    private $accessByReflection = false;

    /**
     * @return bool
     */
    public function isSkipExtraAttributes(): bool
    {
        return $this->skipExtraAttributes;
    }

    /**
     * @param bool $skipExtraAttributes
     * @return DenormalizationBaseSettings
     */
    public function setSkipExtraAttributes(bool $skipExtraAttributes): DenormalizationBaseSettings
    {
        $this->skipExtraAttributes = $skipExtraAttributes;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnableAutoCasting(): bool
    {
        return $this->enableAutoCasting;
    }

    /**
     * @param bool $enableAutoCasting
     * @return DenormalizationBaseSettings
     */
    public function setEnableAutoCasting(bool $enableAutoCasting): DenormalizationBaseSettings
    {
        $this->enableAutoCasting = $enableAutoCasting;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCreateOrUpdate(): bool
    {
        return $this->createOrUpdate;
    }

    /**
     * @param bool $createOrUpdate
     * @return DenormalizationBaseSettings
     */
    public function setCreateOrUpdate(bool $createOrUpdate): DenormalizationBaseSettings
    {
        $this->createOrUpdate = $createOrUpdate;
        return $this;
    }

    /**
     * @return \string[]
     */
    public function getAccessByReflectionList(): array
    {
        return $this->accessByReflectionList;
    }

    /**
     * @param \string[] $accessByReflectionList
     * @return DenormalizationBaseSettings
     */
    public function setAccessByReflectionList(array $accessByReflectionList): DenormalizationBaseSettings
    {
        $this->accessByReflectionList = $accessByReflectionList;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAccessByReflection(): bool
    {
        return $this->accessByReflection;
    }

    /**
     * @param bool $accessByReflection
     * @return DenormalizationBaseSettings
     */
    public function setAccessByReflection(bool $accessByReflection): DenormalizationBaseSettings
    {
        $this->accessByReflection = $accessByReflection;
        return $this;
    }
}