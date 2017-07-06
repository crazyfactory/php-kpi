<?php

namespace CrazyFactory\Kpi;


class SensorState
{
    /**
     * @param array $array
     *
     * @return SensorState
     */
    public static function fromArray($array)
    {
        $key = isset($array["key"])
            ? $array["key"]
            : null;
        $value = isset($array["value"])
            ? $array["value"]
            : null;
        $duration = isset($array["duration"])
            ? $array["duration"]
            : null;
        $timestamp = isset($array["timestamp"])
            ? $array["timestamp"]
            : null;

        return new SensorState($key, $value, $duration, $timestamp);
    }

    /**
     * @var string $key
     */
    protected $key;

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
     * @param string $key
     * @param string $value
     * @param float  $duration
     * @param int    $timestamp
     */
    public function __construct($key, $value, $duration, $timestamp)
    {
        $this->timestamp = $timestamp;
        $this->value = $value;
        $this->key = $key;
        $this->duration = $duration;
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
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            "key" => $this->key,
            "value" => $this->value,
            "duration" => $this->duration,
            "timestamp" => $this->timestamp,
        ];
    }
}
