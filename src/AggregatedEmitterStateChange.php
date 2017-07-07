<?php

namespace CrazyFactory\Kpi;


use Traversable;

class AggregatedEmitterStateChange implements \ArrayAccess, \IteratorAggregate
{
    /**@var EmitterStateChange[] $emitterStateChanges */
    protected $emitterStateChanges;

    /** @var AggregatedEmitterState $aggState */
    protected $aggState;

    /** @var AggregatedEmitterState $lastAggState */
    protected $lastAggState;

    /** @var int $timestamp */
    protected $timestamp;

    /**
     * AggregatedResult constructor.
     *
     * @param AggregatedEmitterState $aggState
     * @param AggregatedEmitterState $lastAggState
     */
    public function __construct(AggregatedEmitterState $aggState, AggregatedEmitterState $lastAggState)
    {
        $this->aggState = $aggState;
        $this->lastAggState = $lastAggState;
        $this->emitterStateChanges = AggregatedEmitterState::getStateChanges($aggState, $lastAggState);
    }

    /**
     * @return EmitterStateChange[]
     */
    public function getEmitterStateChanges()
    {
        return $this->emitterStateChanges;
    }

    /**
     * @return int
     */
    public function getTimestamp()
    {
        return $this->aggState
            ? $this->aggState->getTimestamp()
            : null;
    }

    /**
     * @return int|null
     */
    public function getLevel()
    {
        $level = null;
        foreach ($this->emitterStateChanges as $emitterStateChange) {
            $escLevel = $emitterStateChange->getLevel();
            if ($escLevel !== null && ($level === null || $escLevel < $level)) {
                $level = $escLevel;
            }
        }

        return $level;
    }

    /**
     * @return int|null
     */
    public function getLastLevel()
    {
        $level = null;
        foreach ($this->emitterStateChanges as $emitterStateChange) {
            $escLevel = $emitterStateChange->getLastLevel();
            if ($escLevel !== null && ($level === null || $escLevel < $level)) {
                $level = $escLevel;
            }
        }

        return $level;
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
        return new \ArrayIterator($this->emitterStateChanges);
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
        return array_key_exists($offset, $this->emitterStateChanges);
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
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return $this->emitterStateChanges[$offset];
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
}
