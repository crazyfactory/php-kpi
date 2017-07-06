<?php

namespace CrazyFactory\Kpi;


use Traversable;

class AggregatedEmitterState implements \ArrayAccess, \IteratorAggregate
{
    /**
     * @var EmitterState[] $emitters
     */
    protected $emitters;

    /**
     * @var int $duration
     */
    protected $duration;

    /**
     * @var int $timestamp
     */
    protected $timestamp;

    /**
     * AggregatedResult constructor.
     *
     * @param EmitterState[] $emitters
     * @param int      $duration
     * @param int|null $timestamp
     */
    public function __construct($emitters, $duration, $timestamp = null)
    {
        $this->emitters = $emitters;
        $this->duration = $duration;
        $this->timestamp = $timestamp
            ?: time();
    }

    /**
     * @return int
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @return EmitterState[]
     */
    public function getEmitters()
    {
        return $this->emitters;
    }

    /**
     * Whether a offset exists
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param mixed $offset <p>
     *                      An offset to check for.
     *                      </p>
     *
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return isset($this->emitters[$offset]);
    }

    /**
     * Offset to retrieve
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetget.php
     *
     * @param mixed $offset <p>
     *                      The offset to retrieve.
     *                      </p>
     *
     * @return EmitterState Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return $this->emitters[$offset];
    }

    /**
     * Offset to set
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param mixed $offset <p>
     *                      The offset to assign the value to.
     *                      </p>
     * @param mixed $value  <p>
     *                      The value to set.
     *                      </p>
     *
     * @return void
     * @throws \Exception
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        throw new \Exception("immutable");
    }

    /**
     * Offset to unset
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @param mixed $offset <p>
     *                      The offset to unset.
     *                      </p>
     *
     * @return void
     * @throws \Exception
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        throw new \Exception("immutable");
    }

    /**
     * Retrieve an external iterator
     *
     * @link  http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->emitters);
    }
}
