<?php

namespace Songuitar\Denormalizer\Common;


interface DenormalizationBaseSettingsInterface
{
    /**
     * @return bool
     */
    public function isSkipExtraAttributes() : bool;

    /**
     * @return bool
     */
    public function isEnableAutoCasting() : bool;

    /**
     * @return bool
     */
    public function isCreateOrUpdate() : bool;

    /**
     * @return bool
     */
    public function isAccessByReflection() : bool;

    /**
     * @return array
     */
    public function getAccessByReflectionList(): array;

    /**
     * @param bool $skipExtraAttributes
     * @return $this
     */
    public function setSkipExtraAttributes(bool $skipExtraAttributes);

    /**
     * @param bool $enableAutoCasting
     * @return mixed
     */
    public function setEnableAutoCasting(bool $enableAutoCasting);

    /**
     * @param bool $createOrUpdate
     * @return mixed
     */
    public function setCreateOrUpdate(bool $createOrUpdate);

    /**
     * @param array $accessByReflectionList
     * @return mixed
     */
    public function setAccessByReflectionList(array $accessByReflectionList);

    /**
     * @param bool $accessByReflection
     * @return mixed
     */
    public function setAccessByReflection(bool $accessByReflection);
}