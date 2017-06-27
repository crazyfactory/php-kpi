<?php

namespace CrazyFactory\MicroMetrics;


abstract class Aggregator implements IMetrics
{
	private $name;
	private $current;
	private $previous;

	/**
	 * Aggregator constructor.
	 * @param string $name optinal custom name, fallback is the classname
	 */
	public function __construct($name = 'aggregator_default')
	{
		$this->name = $name;
		$this->current = array();
		$this->previous = array();
	}

	/**
	 * aggregates the data for a specific metric
	 * @return array
	 */
	abstract public function aggregate();

	/**
	 * get the data of the current run
	 * @return array
	 */
	public function getCurrent()
	{
		return $this->current;
	}

	/**
	 * get the data of the previous runs
	 * @return array
	 */
	public function getPrevious()
	{
		return $this->previous;
	}

	/**
	 * provides the name of the Aggregator Instance
	 * @return string $name
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * sets the data for the current run
	 * @param $data_current_run
	 * @return array
	 */
	public function setCurrent($data_current_run)
	{
		$this->current = $data_current_run;
		return $this->current;
	}

	/**
	 * sets the data of the previous runs
	 * @param $data_previous_runs
	 * @return array
	 */
	public function setPrevious($data_previous_runs)
	{
		$this->previous = $data_previous_runs;
		return $this->previous;
	}

	/**
	 * set the name of the Aggregator instance
	 * @param $name of the Aggregator instance
	 * @return string $name
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this->name;
	}

}