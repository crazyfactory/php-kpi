<?php

namespace CrazyFactory\Kpi;


interface StateRetrieverInterface
{
    /**
     * @param string   $sensorName
     * @param int|null $startTime
     * @param int|null $endTime
     * @param int      $limit
     *
     * @return SensorState[]
     */
    public function getSensorStates($sensorName, $startTime = null, $endTime = null, $limit = 10);

    /**
     * @param string   $name
     * @param int|null $startTime
     * @param int|null $endTime
     * @param int      $limit
     *
     * @return EmitterState[]
     */
    public function getEmitterStates($name, $startTime = null, $endTime = null, $limit = 10);

    /**
     * @return AggregatedSensorState|null
     */
    public function getLastAggregatedSensorState();

    /**
     * @return AggregatedSensorState|null
     */
    public function getLastAggregatedEmitterState();
}
