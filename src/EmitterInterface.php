<?php

namespace CrazyFactory\Kpi;


interface EmitterInterface
{
    /**
     * @param StateRetrieverInterface $stateRetriever
     *
     * @return void
     */
    public function setStateRetriever(StateRetrieverInterface $stateRetriever);

    /**
     * @param AggregatedSensorState      $result
     * @param AggregatedSensorState|null $lastResult
     * @param EmitterState|null          $lastState
     *
     * @return EmitterState|int
     */
    public function emit(AggregatedSensorState $result, AggregatedSensorState $lastResult, EmitterState $lastState = null);

    /**
     * @param EmitterState|null $lastState
     *
     * @return bool
     */
    public function shouldEmit(EmitterState $lastState = null);
}
