<?php

namespace CrazyFactory\Kpi;

class SensorState
{
    /**
     * @var string $value
     */
    protected $value;
    /**
     * @var int $timestamp
     */
    protected $timestamp;
    /**
     * @var float
     */
    protected $duration;

    /**
     * SensorState constructor.
     *
     * @param string $value
     * @param float  $duration
     * @param int    $timestamp
     */
    public function __construct($value, $duration, $timestamp)
    {
        $this->timestamp = $timestamp;
        $this->value = $value;
        $this->duration = $duration;
    }

    /**
     * @param array $array
     *
     * @return SensorState
     */
    public static function fromArray($array)
    {
        $value = isset($array['value'])
            ? $array['value']
            : null;
        $duration = isset($array['duration'])
            ? $array['duration']
            : null;
        $timestamp = isset($array['timestamp'])
            ? $array['timestamp']
            : null;

        return new SensorState($value, $duration, $timestamp);
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
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
     * @return array
     */
    public function toArray()
    {
        return [
            'value' => $this->value,
            'duration' => $this->duration,
            'timestamp' => $this->timestamp,
        ];
    }
}
