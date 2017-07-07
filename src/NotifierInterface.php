<?php

namespace CrazyFactory\Kpi;


interface NotifierInterface
{
    /**
     * @param AggregatedEmitterStateChange $aggregatedEmitterStateChange
     *
     * @return void
     */
    public function notify(AggregatedEmitterStateChange $aggregatedEmitterStateChange);
}
