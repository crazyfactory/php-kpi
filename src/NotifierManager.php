<?php

namespace CrazyFactory\Kpi;


abstract class NotifierManager
{

    /**
     * @param AggregatedEmitterStateChange $aggregatedEmitterStateChange
     *
     * @throws \Exception
     */
    public function notify(AggregatedEmitterStateChange $aggregatedEmitterStateChange)
    {
        $map = $this->getNotifierMap();

        foreach ($map as $name => $classNameOrInstance) {
            // Sensors should really exist. This is a rare case where a complete stop is welcome.
            if (!class_exists($classNameOrInstance)) {
                throw new \Exception("notifier class " . $name . " not found");
            }

            /* @var \CrazyFactory\Kpi\NotifierInterface $notifier */
            $notifier = $classNameOrInstance instanceof NotifierInterface
                ? $classNameOrInstance
                : new $classNameOrInstance();

            $notifier->notify($aggregatedEmitterStateChange);
        }
    }

    /**
     * @return string|object[]
     */
    protected function getNotifierMap()
    {
        $map = [];
        $classes = $this->getNotifiers();
        foreach ($classes as $classNameOrInstance) {
            $className = $classNameOrInstance instanceof NotifierInterface
                ? get_class($classNameOrInstance)
                : $classNameOrInstance;

            $name = substr($className, strrpos($className, "\\") + 1, -strlen('Notifier'));
            $map[$name] = $classNameOrInstance;
        }

        return $map;
    }

    /**
     * @return string[]|object[]
     */
    abstract protected function getNotifiers();
}
