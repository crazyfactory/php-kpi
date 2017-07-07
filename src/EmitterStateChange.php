<?php

namespace CrazyFactory\Kpi;


class EmitterStateChange
{
    /** @var $lastState EmitterState */
    protected $lastState;
    /** @var $state EmitterState */
    protected $state;

    /**
     * EmitterStateChange constructor.
     *
     * @param EmitterState $state
     * @param EmitterState $lastState
     */
    public function __construct(EmitterState $state, EmitterState $lastState)
    {
        $this->state = $state;
        $this->lastState = $lastState;
    }

    /**
     * @param EmitterState $state
     * @param EmitterState $lastState
     *
     * @return EmitterStateChange
     */
    public static function createIfDifferent(EmitterState $state, EmitterState $lastState)
    {
        // Exactly one state is actually null.
        if ($state === null xor $lastState === null) {
            return new EmitterStateChange($state, $lastState);
        }

        // Log Level is different
        if ($state->getLevel() !== $lastState->getLevel()) {
            return new EmitterStateChange($state, $lastState);
        }

        return null;
    }

    /**
     * @return EmitterState
     */
    public function getLastState()
    {
        return $this->lastState;
    }

    /**
     * @return EmitterState
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @return int|null
     */
    public function getLevel()
    {
        return $this->state
            ? $this->state->getLevel()
            : null;
    }

    /**
     * @return int|null
     */
    public function getLastLevel()
    {
        return $this->lastState
            ? $this->lastState->getLevel()
            : null;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->state
            ? $this->state->getMessage()
            : '';
    }
}
