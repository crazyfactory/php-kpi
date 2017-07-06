<?php

namespace CrazyFactory\MicroMetrics;


abstract class Emitter implements EmitterInterface
{
    /** @var StateRetrieverInterface $stateRetriever */
    protected $stateRetriever;

    /**
     * @param StateRetrieverInterface|null $stateRetriever
     */
    public function setStateRetriever(StateRetrieverInterface $stateRetriever = null) {
        $this->stateRetriever = $stateRetriever;
    }
}
