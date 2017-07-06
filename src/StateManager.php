<?php

namespace CrazyFactory\MicroMetrics;


abstract class StateManager implements StateRetrieverInterface
{

    /**
     * @param SensorState $sensorState
     *
     * @return void
     */
    abstract public function storeSensorState(SensorState $sensorState);

    /**
     * @param EmitterState $emitterState
     *
     * @return void
     */
    abstract public function storeEmitterState(EmitterState $emitterState);

    /**
     * @param AggregatedSensorState $result
     *
     * @return void
     */
    abstract public function storeAggregatedSensorState(AggregatedSensorState $result);

    /**
     * @param AggregatedEmitterState $result
     *
     * @return void
     */
    abstract public function storeAggregatedEmitterState(AggregatedEmitterState $result);
}
