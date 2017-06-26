<?php

namespace CrazyFactory\MicroMetrics;


abstract class Sensor implements IMetrics
{
	private $name;

	/**
	 * Sensor constructor.
	 * @param string $name
	 */
	public function __construct($name = 'sensor')
	{
		$this->name = $name;
	}

	/**
	 * @return string $name of the sensor instance
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * sets a string to be name of the sensor instance
	 * @param $name
	 * @return string $name of the sensor instance
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this->name;
	}

	/**
	 * @return mixed
	 */
	abstract public function validate();
}