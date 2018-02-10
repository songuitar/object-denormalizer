<?php

namespace Songuitar\Denormalizer\Exception;

class DenormalizationException extends \Exception
{
    /**
     * @var array|null
     */
    private $propertyNames;

    /**
     * @var mixed|null
     */
    private $invalidValue;

    /**
     * NormalizationException constructor.
     *
     * @param string       $message
     * @param array | null $propertyNames
     * @param null|mixed   $invalidValue
     */
    public function __construct($message = '', array $propertyNames = null, $invalidValue = null)
    {
        $this->propertyNames = $propertyNames;
        $this->invalidValue = $invalidValue;

        parent::__construct($message);
    }

    /**
     * Which property is failed to transform.
     *
     * @return array
     */
    public function getPropertyNames()
    {
        return $this->propertyNames;
    }

    /**
     * @return mixed
     */
    public function getInvalidValue()
    {
        return $this->invalidValue;
    }
}
