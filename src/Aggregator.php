<?php

namespace CrazyFactory\MicroMetrics;


abstract class Aggregator implements IMetrics
{

	abstract public static function aggregate();
}