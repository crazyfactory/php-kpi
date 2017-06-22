<?php

namespace CrazyFactory\MicroMetrics;


class MicroMetrics
{
	private $name;

	/**
	 * Task constructor.
	 * @param string $name the name of the task
	 */
	public function __construct( $name)
	{
		$this->name=$name;
	}

	public function getName()
	{
		return $this->name;
	}

}
