<?php

namespace CrazyFactory\Kpi;


abstract class EmitterManager
{
    /**
     * @var StateManager $stateManager
     */
    protected $stateManager;

    /**
     * MetricsAggregator constructor.
     *
     * @param StateManager $stateManager
     */
    public function __construct(StateManager $stateManager = null) {
        $this->stateManager = $stateManager;
    }

    /**
     * @return string[]
     */
    abstract protected function getEmitterClasses();

    /**
     * @return string[]
     */
    protected function getEmitterMap() {

        $map = [];
        $classes = $this->getEmitterClasses();
        foreach ($classes as $className) {
            $key = substr($className, strrpos($className, "\\") + 1, -strlen('Emitter'));
            $map[$key] = $className;
        }

        return $map;
    }

    /**
     * @param AggregatedSensorState|null  $aggSensorState
     * @param AggregatedSensorState|null  $lastAggSensorState
     * @param AggregatedEmitterState|null $lastAggEmitterState
     *
     * @return AggregatedEmitterState
     * @throws \Exception
     */
    public function aggregate(AggregatedSensorState $aggSensorState = null, AggregatedSensorState $lastAggSensorState = null, AggregatedEmitterState $lastAggEmitterState = null) {

        $begin = microtime(true);
        $map = $this->getEmitterMap();

        $emitterStates = [];

        foreach ($map as $name => $className) {
            // Sensors should really exist. This is a rare case where a complete stop is welcome.
            if (!class_exists($className)) {
                throw new \Exception("emitter class " . $name . " not found");
            }

            try {
                /* @var \CrazyFactory\Kpi\Emitter $emitter */
                $emitter = new $className();
                $emitter->setStateRetriever($this->stateManager);
                $lastEmitterState = isset($lastAggEmitterState[$name])
                    ? $lastAggEmitterState[$name]
                    : null;

                $value = $emitter->shouldEmit($lastEmitterState)
                    ? $emitter->emit($aggSensorState, $lastAggSensorState, $lastEmitterState)
                    : $lastEmitterState;

                $emitterStates[$name] = $value instanceof EmitterState
                    ? $value
                    : new EmitterState(time(), $value);
            }
            catch (\Exception $e) {
                $aggSensorState[$name] = null;
            }
        }

        $duration = microtime(true) - $begin;
        return new AggregatedEmitterState($emitterStates, $duration, time());
    }
}
