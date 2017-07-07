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
    public function __construct(StateManager $stateManager = null)
    {
        $this->stateManager = $stateManager;
    }

    /**
     * @param AggregatedSensorState|null  $aggSensorState
     * @param AggregatedSensorState|null  $lastAggSensorState
     * @param AggregatedEmitterState|null $lastAggEmitterState
     *
     * @return AggregatedEmitterState
     * @throws \Exception
     */
    public function aggregate(AggregatedSensorState $aggSensorState = null, AggregatedSensorState $lastAggSensorState = null, AggregatedEmitterState $lastAggEmitterState = null)
    {
        $begin = microtime(true);
        $map = $this->getEmitterMap();

        $emitterStates = [];

        foreach ($map as $name => $classNameOrInstance) {
            // Sensors should really exist. This is a rare case where a complete stop is welcome.
            if (!is_object($classNameOrInstance) && !class_exists($classNameOrInstance)) {
                throw new \Exception("emitter class " . $name . " not found");
            }

            try {
                /* @var \CrazyFactory\Kpi\Emitter $emitter */
                $emitter = $classNameOrInstance instanceof EmitterInterface
                    ? $classNameOrInstance
                    : new $classNameOrInstance();
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
            } catch (\Exception $e) {
                $aggSensorState[$name] = null;
            }
        }

        $duration = microtime(true) - $begin;

        return new AggregatedEmitterState($emitterStates, $duration, time());
    }

    /**
     * @return string[]|object[]
     */
    protected function getEmitterMap()
    {
        $map = [];
        $classes = $this->getEmitters();
        foreach ($classes as $classNameOrInstance) {
            $className = $classNameOrInstance instanceof EmitterInterface
                ? get_class($classNameOrInstance)
                : $classNameOrInstance;

            $name = substr($className, strrpos($className, "\\") + 1, -strlen('Emitter'));
            $map[$name] = $classNameOrInstance;
        }

        return $map;
    }

    /**
     * @return string[]|object[]
     */
    abstract protected function getEmitters();
}
