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
        $name = isset($array["name"])
            ? $array["name"]
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

        return new SensorState($name, $value, $duration, $timestamp);
    }

    /**
     * @var string $name
     */
    protected $name;

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
     * @param string $name
     * @param string $value
     * @param float  $duration
     * @param int    $timestamp
     */
    public function __construct($name, $value, $duration, $timestamp)
    {
        $this->timestamp = $timestamp;
        $this->value = $value;
        $this->name = $name;
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            "name" => $this->name,
            "value" => $this->value,
            "duration" => $this->duration,
            "timestamp" => $this->timestamp,
        ];
    }
}
