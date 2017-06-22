<?php

namespace CrazyFactory\MicroMetrics;


use CrazyFactory\ShopApi\Exception;

class MicroMetrics
{
	private $name;
	private $treshold;
	private $taskQueue=array();

	/**
	 * Task constructor.
	 * @param string $name the name of the task
	 * @param int $treshold_in_minutes : pauses between runs for this amount in minutes
	 */
	public function __construct( $name, $treshold_in_minutes=5)
	{
		$this->name=$name;
		$this->treshold=$treshold_in_minutes;
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
	 * shifts the task from the start of the queue
	 * @return mixed next task to process
	 */
	public function getNextTask()
	{
		return array_shift($this->taskQueue);
	}

	/**
	 *
	 */
	public function runTasks()
	{
		foreach($this->taskQueue as $task)
		{
			// run the task
			try{
				$task->run();
			}
			catch (Exception $e) {
				$this->notify();
			}

			// validate the task
			try{
				$task->validate();
			}
			catch (Exception $e) {
				$this->notify();
			}
		}
	}

	private function notify($msg,$data)
	{
		echo "<h2>Notification</h2>";
		echo "<h3>$msg</h3>";
		var_dump($data);
	}

}
