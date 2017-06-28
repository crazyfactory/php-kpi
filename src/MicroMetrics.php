<?php

namespace CrazyFactory\MicroMetrics;


abstract class MicroMetrics
{
	protected $aggregator = array();
	protected $aggregatorQueue = array();
	protected $lastCheck;
	protected $sensorQueue= array();
	protected $started;
	protected $treshold;

	/**
	 * MicoMetrics constructor
	 * @param int $last_checked : timestamp
	 * @param int $treshold_in_minutes : pauses between runs for this amount in minutes
	 */
	public function __construct( $last_checked=0, $treshold_in_minutes=5)
	{
		$this->lastCheck = $last_checked;
		$this->treshold = $treshold_in_minutes;
		$this->started = microtime(true);
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
	 * exposes data collected by Aggregators
	 * @return array $aggregator contains data collected by all Aggregators
	 */
	public function getAggregatedData()
	{
		return $this->aggregator;
	}

	public function getStartTime()
	{
		return $this->started;
	}

	/**
	 * implements the notification via slack & email
	 * @param array $data
	 * @return bool
	 */
	abstract protected function notify($data);

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
	 * runs the queued tasks one-by-one
	 * validated if the last check is long enough ago ($this->proceedExecution return true)
	 * @return void
	 */
	public function runAggregators()
	{
		if(self::ready($this->lastCheck, $this->treshold)){
			foreach($this->aggregatorQueue as $aggregator)
			{
				// run the aggregator
				$aggregator_name = $aggregator->getName();

				try{
					$this->aggregator[$aggregator_name]=$aggregator->aggregate();
				}
				catch (\Exception $e) {
					$error=array();
					$error['name']=$aggregator_name;
					$error['value']=$e;
					$error['log_level']=E_ERROR;
					$error['duration']=microtime(true)-(intval($this->getStartTime()));
					$this->notify($error);
				}
			}

		}
		return $this->aggregator;
	}

	/**
	 * calls 'validate' methode of all sensors in sensorQueue
	 * @param array $aggregator_data is the collected data of the aggregators just run
	 * @return array with the results given back from
	 */
	public function runSensors($aggregator_data)
	{
		$response = array();
		foreach($this->sensorQueue as $sensor)
		{
			$sensor_name = $sensor->getName();
			try{
				$sensor_result = $sensor->validate($aggregator_data);
				$this->saveLog($sensor_result);
				$this->notify($sensor_result);
				$response[$sensor_name] = $sensor_result;
			}
			catch (\Exception $e) {
				$error=array();
				$error['name']=$sensor_name;
				$error['value']=$e;
				$error['log_level']=E_ERROR;
				$error['duration']=microtime(true)-(intval($this->getStartTime()));
				$this->notify($error);
			}
		}
		return $response;

	}

	abstract protected function saveLog($data);

	/**
	 * sets an array as queues tasks
	 * this potentially override tasks set with MicroMetrics->addTask
	 * @param array $aggregator_queue
	 * @return array
	 * @throws \Exception
	 */
	public function setAggregatorQueue($aggregator_queue)
	{
		if(is_array($aggregator_queue))
		{
			$this->aggregatorQueue=$aggregator_queue;
		}
		else{
			throw new \Exception('MicroMetrics->setAggregatorQueue called with non-array parameter', E_ERROR);
		}
		return $this->aggregatorQueue;
	}

	/**
	 * set a sensor to queue
	 * @param array $sensor_queue
	 * @return array $this->sensorQueue
	 * @throws \Exception
	 */
	public function setSensorQueue($sensor_queue)
	{
		if(is_array($sensor_queue))
		{
			$this->sensorQueue = $sensor_queue;
		}
		else{
			throw new \Exception('MicroMetrics->setAggregatorQueue called with non-array parameter', E_ERROR);
		}
		return $this->sensorQueue;
	}


}
