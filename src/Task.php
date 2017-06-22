<?php

namespace CrazyFactory\MicroMetrics;


class Task
{
	private $name;
	public $data;

	/**
	 * Task constructor.
	 * @param string $name the name of the task
	 */
	public function __construct( $name, $run_method, $validate_method, $data_last_run)
	{
		$this->name=$name;
		$this->run = $run_method;
		$this->validate = $validate_method;
		$this->data = $data_last_run;
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

