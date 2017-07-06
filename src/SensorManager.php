<?php

namespace CrazyFactory\Kpi;

abstract class SensorManager
{
    /**
     * @return string[]
     */
    abstract protected function getSensorClasses();

    /**
     * @return string[]
     */
    protected function getSensorMap() {

        $map = [];
        $classes = $this->getSensorClasses();
        foreach ($classes as $className) {
            $key = substr($className, strrpos($className, "\\") + 1, -strlen('Sensor'));
            $map[$key] = $className;
        }

        return $map;
    }

    /**
     * @param AggregatedSensorState|null $aggSensorState
     *
     * @return AggregatedSensorState
     * @throws \Exception
     */
    public function aggregate(AggregatedSensorState $aggSensorState = null) {

        $begin = microtime(true);
        $result = array();
        $map = $this->getSensorMap();

        foreach ($map as $name => $className) {
            // Sensors should really exist. This is a rare case where a complete stop is welcome.
            if (!class_exists($className)) {
                throw new \Exception("sensor class " . $name . " not found");
            }

            try {
                $beginSensor = microtime(true);
                /* @var \CrazyFactory\Kpi\SensorInterface $sensor */
                $sensor = new $className();
                $lastState = isset($aggSensorState[$name])
                    ? $aggSensorState[$name]
                    : null;

                $value = $sensor->shouldSense($lastState)
                    ? $sensor->sense($lastState)
                    : $lastState;
                $sensorDuration = microtime(true) - $beginSensor;

                $result[$name] = new SensorState($name, $value, $sensorDuration, time());
            }
            catch (\Exception $e) {
                $result[$name] = null;
            }
        }

        $duration = microtime(true) - $begin;
        return new AggregatedSensorState($result, $duration, time());
    }
}
