<?php

namespace CrazyFactory\Kpi;

use Traversable;

class AggregatedSensorState implements \ArrayAccess, \IteratorAggregate
{
    /**
     * @var SensorState[] $sensors
     */
    protected $sensors;
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
     * @param SensorState[] $sensors
     * @param int           $duration
     * @param int|null      $timestamp
     */
    public function __construct($sensors = [], $duration = null, $timestamp = null)
    {
        $this->sensors = $sensors;
        $this->duration = $duration;
        $this->timestamp = $timestamp
            ?: time();
    }

    /**
     * @param array $array
     *
     * @return AggregatedSensorState
     */
    public static function fromArray($array)
    {
        $sensors = null;

        // We need to preserve the keys so we can't use array_map
        if (isset($array['sensors']) && is_array($array['sensors'])) {
            $sensors = [];
            foreach ($array['sensors'] as $name => $data) {
                $sensors[$name] = SensorState::fromArray($data);
            }
        }

        $duration = isset($array['duration'])
            ? $array['duration']
            : null;

        $timestamp = isset($array['timestamp'])
            ? $array['timestamp']
            : null;

        return new AggregatedSensorState($sensors, $duration, $timestamp);
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
     * @return SensorState[]
     */
    public function getSensors()
    {
        return $this->sensors;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $list = null;
        if (is_array($this->sensors)) {
            $list = [];
            foreach ($this->sensors as $name => $sensor) {
                $list[$name] = $sensor->toArray();
            }
        }

        return [
            'duration' => $this->duration,
            'timestamp' => $this->timestamp,
            'sensors' => $list,
        ];
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
        return isset($this->sensors[$offset]);
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
     * @return SensorState Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return $this->sensors[$offset];
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
        throw new \Exception('immutable');
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
        throw new \Exception('immutable');
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
        return new \ArrayIterator($this->sensors);
    }
}
