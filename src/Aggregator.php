<?php

namespace CrazyFactory\MicroMetrics;


abstract class Aggregator implements IMetrics
{
	private $name;

	/**
	 * Aggregator constructor.
	 * @param string $name optinal custom name, fallback is the classname
	 */
	public function __construct($name = null)
	{
		if(!$name)
		{
			$name = get_class($this);
		}
		$this->name=$name;
	}

	abstract public static function aggregate();

	/**
	 * provides the name of the Aggregator Instance
	 * @return string $name
	 */
	public function getName()
	{
		return $this->name;
	}

	public function setName($name)
	{
		$this->name = $name;
		return $this->name;
	}
}