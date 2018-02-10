<?php

namespace Songuitar\Denormalizer\Service;

class NormalizedDataStructureResolver implements NormalizedDataStructureResolverInterface
{
    /**
     * @param mixed $data
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    public function resolve($data) : string
    {
        if (!is_array($data)) {
            if (is_int($data)) {
                return self::TYPE_IDENTIFIER;
            }

            return self::TYPE_OTHER_SCALAR;
        }

        if ($this->isEmpty($data)) {
            return self::TYPE_EMPTY;
        }

        if ($data instanceof \stdClass || $this->isAssociative($data)) {
            return self::TYPE_NORMALIZED_OBJECT;
        }

        if ($this->isSequential($data)) {
            if ($this->isArrayOf($data, self::IDENTIFIER_BUILTIN_TYPE)) {
                return self::TYPE_IDENTIFIER_MULTIPLE;
            }
            if (
            $this->isArrayOf(
                $data,
                null,
                function ($value) {
                    return is_array($value) && $this->isAssociative($value);
                }
            )
            ) {
                return self::TYPE_NORMALIZED_OBJECT_MULTIPLE;
            }

            return self::TYPE_MIXED;
        }

        throw new \InvalidArgumentException('this type of value is not supported');
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    public function isSequential(array $data)
    {
        return array_keys($data) === range(0, count($data) - 1);
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    public function isEmpty(array $data)
    {
        return empty(array_filter($data, function ($value) {
            return $value || is_bool($value) || is_array($value);
        }));
    }

    /**
     * @param array         $data
     * @param string        $type
     * @param null|\Closure $checker
     *
     * @throws \InvalidArgumentException
     *
     * @return bool
     */
    public function isArrayOf(array $data, string $type = null, \Closure $checker = null)
    {
        if (!$checker && !$type) {
            throw new \InvalidArgumentException('at least one of arguments (type, checker) should be set');
        }
        $checker = $checker ?: function ($value) use ($type) {
            return gettype($value) === $type;
        };

        return 0 === count(
                array_filter(
                    $data,
                    function ($value) use ($checker) {
                        return !$checker($value);
                    }
                )
            );
    }

    /**
     * @param array $data
     *
     * @throws \InvalidArgumentException
     *
     * @return bool
     */
    public function isAssociative(array $data)
    {
        return $this->isArrayOf(array_keys($data), 'string');
    }
}
