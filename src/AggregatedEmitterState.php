<?php

namespace CrazyFactory\Kpi;

use Traversable;

class AggregatedEmitterState implements \ArrayAccess, \IteratorAggregate
{
	/**
	 * @var EmitterState[] $emitters
	 */
	protected $emitters;
	/**
	 * @var int $duration
	 */
	protected $duration;
	/**
	 * @var int $timestamp
	 */
	protected $timestamp;

	/**
	 * AggregatedResult constructor.
	 *
	 * @param EmitterState[] $emitters
	 * @param int $duration
	 * @param int|null $timestamp
	 */
	public function __construct($emitters = array(), $duration = null, $timestamp = null)
	{
		$this->emitters = $emitters;
		$this->duration = $duration;
		$this->timestamp = $timestamp
			?: time();
	}

	/**
	 * @param AggregatedEmitterState $aggState
	 * @param AggregatedEmitterState $lastAggState
	 *
	 * @return EmitterStateChange[]
	 */
	public static function getStateChanges(AggregatedEmitterState $aggState, AggregatedEmitterState $lastAggState)
	{

		// Get names from both states if existing
		$states = $aggState !== null
			? $aggState->getEmitters()
			: array();
		$lastStates = $lastAggState !== null
			? $lastAggState->getEmitters()
			: array();

		// Get all names from both states
		$names = array_unique(array_merge(array_keys($states), array_keys($lastStates)));

		// Compare elements from aggState and lastAggState by name and try create a EmitterStateChange instance.
		$stateChanges = array();

		foreach ($names as $name) {
			$emitterState = isset($states[$name])
				? $states[$name]
				: null;
			$lastEmitterState = isset($lastStates[$name])
				? $lastStates[$name]
				: null;

			if ($stateChanged = EmitterStateChange::createIfDifferent($emitterState, $lastEmitterState)) {
				$stateChanges[$name] = $stateChanged;
			}
		}

		// Return all created instances.
		return $stateChanges;
	}

	/**
	 * @return EmitterState[]
	 */
	public function getEmitters()
	{
		return $this->emitters;
	}

	/**
	 * @param array $array
	 *
	 * @return AggregatedEmitterState
	 */
	public static function fromArray($array)
	{
		$emitters = null;
		// We need to preserve the names so we can't use array_map
		if (isset($array['emitters']) && is_array($array['emitters'])) {
			$emitters = array();
			foreach ($array['emitters'] as $name => $data) {
				$emitters[$name] = EmitterState::fromArray($data);
			}
		}

		$duration = isset($array['duration'])
			? $array['duration']
			: null;

		$timestamp = isset($array['timestamp'])
			? $array['timestamp']
			: null;

		return new AggregatedEmitterState($emitters, $duration, $timestamp);
	}

	/**
	 * @return int
	 */
	public function getTimestamp()
	{
		return $this->timestamp;
	}

	/**
	 * @return int
	 */
	public function getDuration()
	{
		return $this->duration;
	}

	/**
	 * @return array
	 */
	public function toArray()
	{
		$list = null;
		if (is_array($this->emitters)) {
			$list = array();
			foreach ($this->emitters as $name => $emitter) {
				$list[$name] = $emitter->toArray();
			}
		}

		return array(
			'duration' => $this->duration,
			'timestamp' => $this->timestamp,
			'emitters' => $list,
			'level' => $this->getLevel(),
		);
	}

	/**
	 * @return int|null
	 */
	public function getLevel()
	{
		$level = null;
		foreach ($this->emitters as $emitter) {
			$escLevel = $emitter->getLevel();
			if ($escLevel !== null && ($level === null || $escLevel < $level)) {
				$level = $escLevel;
			}
		}

		return $level;
	}

	/**
	 * Whether a offset exists
	 *
	 * @link  http://php.net/manual/en/arrayaccess.offsetexists.php
	 *
	 * @param mixed $offset <p>
	 *                      An offset to check for.
	 *                      </p>
	 *
	 * @return boolean true on success or false on failure.
	 * </p>
	 * <p>
	 * The return value will be casted to boolean if non-boolean was returned.
	 * @since 5.0.0
	 */
	public function offsetExists($offset)
	{
		return isset($this->emitters[$offset]);
	}

	/**
	 * Offset to retrieve
	 *
	 * @link  http://php.net/manual/en/arrayaccess.offsetget.php
	 *
	 * @param mixed $offset <p>
	 *                      The offset to retrieve.
	 *                      </p>
	 *
	 * @return EmitterState Can return all value types.
	 * @since 5.0.0
	 */
	public function offsetGet($offset)
	{
		return $this->emitters[$offset];
	}

	/**
	 * Offset to set
	 *
	 * @link  http://php.net/manual/en/arrayaccess.offsetset.php
	 *
	 * @param mixed $offset <p>
	 *                      The offset to assign the value to.
	 *                      </p>
	 * @param mixed $value <p>
	 *                      The value to set.
	 *                      </p>
	 *
	 * @return void
	 * @throws \Exception
	 * @since 5.0.0
	 */
	public function offsetSet($offset, $value)
	{
		throw new \Exception('immutable');
	}

	/**
	 * Offset to unset
	 *
	 * @link  http://php.net/manual/en/arrayaccess.offsetunset.php
	 *
	 * @param mixed $offset <p>
	 *                      The offset to unset.
	 *                      </p>
	 *
	 * @return void
	 * @throws \Exception
	 * @since 5.0.0
	 */
	public function offsetUnset($offset)
	{
		throw new \Exception('immutable');
	}

	/**
	 * Retrieve an external iterator
	 *
	 * @link  http://php.net/manual/en/iteratoraggregate.getiterator.php
	 * @return Traversable An instance of an object implementing <b>Iterator</b> or
	 * <b>Traversable</b>
	 * @since 5.0.0
	 */
	public function getIterator()
	{
		return new \ArrayIterator($this->emitters);
	}
}
