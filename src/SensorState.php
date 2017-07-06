<?php

namespace CrazyFactory\MicroMetrics;


class SensorState
{
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
     * @param float $duration
     * @param int $timestamp
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
}
