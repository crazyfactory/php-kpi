<?php

namespace CrazyFactory\MicroMetrics;


use CrazyFactory\ShopApi\Exception;

class MicroMetrics
{
	private $aggregator = array();
	private $aggregatorQueue = array();
	private $lastCheck;
	private $name = '';
	private $sensorQueue= array();
	private $treshold;

	/**
	 * MicoMetrics constructor
	 * @param string $name the name of the task
	 * @param int $last_checked : timestamp
	 * @param int $treshold_in_minutes : pauses between runs for this amount in minutes
	 */
	public function __construct( $name, $last_checked=0, $treshold_in_minutes=5)
	{
		$this->name=$name;
		$this->lastCheck=$last_checked;
		$this->treshold=$treshold_in_minutes;
	}

	/**
	 * validates if we are ready to check again
	 * @param $last_check timestamp
	 * @param $treshold_in_minutes
	 * @return bool
	 */
	public static function ready($last_check, $treshold_in_minutes)
	{
		$next_check=$last_check + ($treshold_in_minutes*60);
		$proceed= time() > $next_check ? true : false;
		return $proceed;
	}

	/**
	 * returns the name property of MicroMetrics instance
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Adds a aggregator to its queue
	 * @param $aggregator the new Aggregator to add to the queue
	 * @return array with all queued Aggregators
	 */
	public function addToAggregatorQueue($aggregator)
	{
		$this->aggregatorQueue[]=$aggregator;
		return $this->aggregatorQueue;
	}

	/**
	 * adds a Sensor to Queue to process
	 * @param $sensor
	 * @return array $sensorQueue
	 */
	public function addToSensorQueue($sensor)
	{
		$this->aggregatorQueue[]=$sensor;
		return $this->sensorQueue;
	}

	/**
	 * sets an array as queues tasks
	 * this potentially override tasks set with MicroMetrics->addTask
	 * @param array $aggregator_queue
	 * @return array
	 * @throws Exception
	 */
	public function setAggregatorQueue($aggregator_queue)
	{
		if(is_array($aggregator_queue))
		{
			$this->aggregatorQueue=$aggregator_queue;
		}
		else{
			throw new Exception('MicroMetrics->setAggregatorQueue called with non-array parameter');
		}
		return $this->aggregatorQueue;
	}

	/**
	 * set a sensor to queue
	 * @param array $sensor_queue
	 * @return array $this->sensorQueue
	 * @throws Exception
	 */
	public function setSensorQueue($sensor_queue)
	{
		if(is_array($sensor_queue))
		{
			$this->sensorQueue = $sensor_queue;
		}
		else{
			throw new Exception('MicroMetrics->setAggregatorQueue called with non-array parameter');
		}
		return $this->sensorQueue;
	}

	/**
	 * shifts an Aggregator from the start of the queue
	 * @return mixed next Aggregator to process
	 */
	public function getNextAggregator()
	{
		return array_shift($this->aggregatorQueue);
	}

	/**
	 * runs the queued tasks one-by-one
	 * validated if the last check is long enough ago ($this->proceedExecution return true)
	 * @return void
	 */
	public function runAggregators()
	{
		if(self::ready()){
			foreach($this->aggregatorQueue as $aggregator)
			{
				// run the aggregator
				$aggregator_name = $aggregator->getName();

				try{
					$this->aggregator[$aggregator_name]=$aggregator->aggregate();
				}
				catch (Exception $e) {
					$this->notify($e);
				}
			}

		}
		return $this->aggregator;
	}



	/**
	 * currently in WIP
	 * @param $msg
	 * @param $data
	 */
	private function notify($msg,$data)
	{
		echo "<h2>Notification</h2>";
		echo "<h3>$msg</h3>";
		var_dump($data);
	}

}
