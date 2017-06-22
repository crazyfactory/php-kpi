<?php

namespace CrazyFactory\MicroMetrics;


use CrazyFactory\ShopApi\Exception;

class MicroMetrics
{
	private $name;
	private $treshold;
	private $taskQueue=array();
	private $lastCheck;

	/**
	 * Task constructor
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
	 * validates if the time is right to run the tasks again
	 * @return bool
	 */
	private function proceedExecution()
	{
		$next_check=$this->lastCheck + ($this->treshold*60);
		$proceed= time() > $next_check ? true : false;
		return $proceed;
	}

	public static function ready($last_check, $treshold_in_minutes)
	{
		$next_check=$last_check + ($treshold_in_minutes*60);
		$proceed= time() > $next_check ? true : false;
		return $proceed;
	}

	/**
	 * returns the name property
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Adds a task to the task queue
	 * @param $task the new task to add to the queue
	 * @return array with all queue tasks
	 */
	public function addTask($task)
	{
		$this->taskQueue[]=$task;
		return $this->taskQueue;
	}

	/**
	 * sets an array as queues tasks
	 * this potentially override tasks set with MicroMetrics->addTask
	 * @param array $task_queue
	 * @return array
	 * @throws Exception
	 */
	public function setTaskQueue($task_queue)
	{
		if(is_array($task_queue))
		{
			$this->taskQueue=$task_queue;
		}
		else{
			throw new Exception('MicroMetrics->setTaskQueue called with non-array parameter');
		}
		return $this->taskQueue;
	}

	/**
	 * shifts the task from the start of the queue
	 * @return mixed next task to process
	 */
	public function getNextTask()
	{
		return array_shift($this->taskQueue);
	}

	/**
	 * runs the queued tasks one-by-one
	 * validated if the last check is long enough ago ($this->proceedExecution return true)
	 * @return void
	 */
	public function runTasks()
	{
		if($this->proceedExecution()){
			foreach($this->taskQueue as $task)
			{
				// run the task
				try{
					$run_result=$task->run();
				}
				catch (Exception $e) {
					$this->notify();
				}

				// validate the task
				try{
					$task->validate($run_result, $task->data);
				}
				catch (Exception $e) {
					$this->notify();
				}
			}
		}

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
