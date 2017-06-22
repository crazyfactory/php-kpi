<?php

namespace CrazyFactory\MicroMetrics;


class Task
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

	/**
	 * allows to run custom methods for each task
	 * @param $method a custom method
	 * @param mixed $args
	 * @return mixed
	 */
	public function __call($method, $args)
	{
		if (isset($this->$method)) {
			$func = $this->$method;
			return call_user_func_array($func, $args);
		}
	}

	/**
	 * gives the name of the task
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}



}

