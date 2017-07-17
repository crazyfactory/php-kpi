<?php

namespace CrazyFactory\Kpi;

abstract class SensorManager
{
    /**
     * @param AggregatedSensorState|null $aggSensorState
     *
     * @return AggregatedSensorState
     * @throws \Exception
     */
    public function aggregate(AggregatedSensorState $aggSensorState = null)
    {
        $begin = microtime(true);
        $result = [];
        $map = $this->getSensorMap();

        foreach ($map as $name => $classNameOrInstance) {
            // Sensors should really exist. This is a rare case where a complete stop is welcome.
            if (!is_object($classNameOrInstance) && !class_exists($classNameOrInstance)) {
                throw new \Exception('sensor class ' . $name . ' not found');
            }

            try {
                $beginSensor = microtime(true);
                /* @var \CrazyFactory\Kpi\SensorInterface $sensor */
                $sensor = $classNameOrInstance instanceof SensorInterface
                    ? $classNameOrInstance
                    : new $classNameOrInstance();
                $lastState = isset($aggSensorState[$name])
                    ? $aggSensorState[$name]
                    : null;

                $value = $sensor->shouldSense($lastState)
                    ? $sensor->sense($lastState)
                    : $lastState;
                $sensorDuration = microtime(true) - $beginSensor;

                $result[$name] = new SensorState($value, $sensorDuration, time());
            }
            catch (\Exception $e) {
                $result[$name] = null;
            }
        }

        $duration = microtime(true) - $begin;

        return new AggregatedSensorState($result, $duration, time());
    }

    /**
     * @return string[]|object[]
     */
    protected function getSensorMap()
    {
        $map = [];
        $classes = $this->getSensors();
        foreach ($classes as $classNameOrInstance) {
            $className = $classNameOrInstance instanceof SensorInterface
                ? get_class($classNameOrInstance)
                : $classNameOrInstance;
            $name = substr($className, strrpos($className, '\\') + 1, -strlen('Sensor'));
            $map[$name] = $classNameOrInstance;
        }

        return $map;
    }

    /**
     * @return string[]|object[]
     */
    abstract protected function getSensors();
}
