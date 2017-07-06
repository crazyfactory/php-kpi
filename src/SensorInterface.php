<?php

namespace CrazyFactory\Kpi;


interface SensorInterface
{
    /**
     * aggregates the data for a specific metric
     *
     * @param SensorState|null $lastState
     *
     * @return string
     */
	public function sense(SensorState $lastState = null);

    /**
     * @param SensorState|null $lastState
     *
     * @return bool
     */
    public function shouldSense(SensorState $lastState = null);
}
