<?php

namespace CrazyFactory\MicroMetrics;


abstract class Sensor implements IMetrics
{
	public $name;

	public function __construct($name = null)
	{
		if(!$name)
		{
			$name = get_class($this);
		}
		$this->name=$name;
	}

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