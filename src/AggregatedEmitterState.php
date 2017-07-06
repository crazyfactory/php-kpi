<?php

namespace CrazyFactory\Kpi;


use Traversable;

class AggregatedEmitterState implements \ArrayAccess, \IteratorAggregate
{
    /**
     * @param AggregatedEmitterState $aggState
     * @param AggregatedEmitterState $lastAggState
     *
     * @return EmitterStateChange[]
     */
    public static function getStateChanges(AggregatedEmitterState $aggState, AggregatedEmitterState $lastAggState) {

        // Get keys from both states if existing
        $states = $aggState !== null
            ? $aggState->emitters
            : [];
        $lastStates = $lastAggState !== null
            ? $lastAggState
            : [];

        // Get all keys from both states
        $keys = array_unique(array_merge(array_keys($states), array_keys($lastStates)));

        // Compare elements from aggState and lastAggState by key and try create a EmitterStateChange instance.
        $stateChanges = [];

        foreach ($keys as $key) {
            $emitterState = isset($states[$key])
                ? $states[$key]
                : null;
            $lastEmitterState = isset($lastStates[$key])
                ? $lastStates[$key]
                : null;

            if ($stateChanged = EmitterStateChange::createIfDifferent($emitterState, $lastEmitterState)) {
                $stateChanged[$key] = $stateChanged;
            }
        }

        // Return all created instances.
        return $stateChanges;
    }

    /**
     * @param array $array
     *
     * @return AggregatedEmitterState
     */
    public static function fromArray($array)
    {
        $emitters = null;
        // We need to preserve the keys so we can't use array_map
        if (isset($array['emitters']) && is_array($array['emitters'])) {
            $emitters = [];
            foreach ($array['emitters'] as $name => $data) {
                $emitters[$name] = EmitterState::fromArray($data);
            }
        }

        $duration = isset($array['duration'])
            ? $array['duration']
            : null;

        $timestamp = isset($array['timestamp'])
            ? $array['timestamp']
            : null;

        return new AggregatedEmitterState($emitters, $duration, $timestamp);
    }

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
     * @param int            $duration
     * @param int|null       $timestamp
     */
    public function __construct($emitters = [], $duration = null, $timestamp = null)
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

    /**
     * @return array
     */
    public function toArray()
    {
        $emitters = null;
        if (is_array($this->emitters)) {
            foreach ($this->emitters as $name => $emitter) {
                $emitters[$name] = $emitter->toArray();
            }
        }

        return [
            "duration" => $this->duration,
            "timestamp" => $this->timestamp,
            "emitters" => $emitters,
        ];
    }
}
