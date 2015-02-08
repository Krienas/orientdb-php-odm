<?php

namespace Doctrine\ODM\OrientDB\Caster;


abstract class AbstractCaster implements CasterInterface
{
    protected $dateClass = '\DateTime';
    protected $value;
    protected $properties = [];

    const SHORT_LIMIT = 32767;
    const LONG_LIMIT = 9223372036854775807;
    const BYTE_MAX_VALUE = 127;
    const BYTE_MIN_VALUE = -128;
    const MISMATCH_MESSAGE = 'trying to cast "%s" as %s';


    /**
     * Sets the internal value to work with.
     *
     * @param mixed $value
     *
     * @return $this
     */
    public function setValue($value) {
        $this->value = $value;

        return $this;
    }

    /**
     * Assigns the class used to cast dates and datetime values.
     * If the $class is a subclass of \DateTimeInterface, it uses it, it uses \DateTime
     * otherwise.
     *
     * @param string $class
     */
    protected function assignDateClass($class) {
        $refClass = new \ReflectionClass($class);

        if (!$refClass->implementsInterface(\DateTimeInterface::class)) {
            throw new \InvalidArgumentException("The class used to cast DATE and DATETIME values must be derived from \\DateTimeInterface");
        }

        $this->dateClass = $class;
    }

    /**
     * Returns the class used to cast date and datetimes.
     *
     * @return string
     */
    protected function getDateClass() {
        return $this->dateClass;
    }

    /**
     * Defines properties that can be internally used by the caster.
     *
     * @param string $key
     * @param array  $mapping
     */
    public function setProperty($key, array $mapping) {
        $this->properties[$key] = $mapping;
    }

    /**
     * Returns a property of the Caster, given its $key.
     *
     * @param  string $key
     *
     * @return array
     */
    protected function getProperty($key) {
        return isset($this->properties[$key]) ? $this->properties[$key] : null;
    }

    /**
     * Throws an exception whenever $value can not be casted as $expectedType.
     *
     * @param string $expectedType
     *
     * @throws CastingMismatchException
     */
    protected function raiseMismatch($expectedType) {
        $value = $this->value;

        if (is_object($value)) {
            $value = get_class($value);
        } elseif (is_array($value)) {
            $value = implode(',', $value);
        }

        throw new CastingMismatchException(sprintf(self::MISMATCH_MESSAGE, $value, $expectedType));
    }
} 