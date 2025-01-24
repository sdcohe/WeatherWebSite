<?php
class hazard
{
	public $period;
	public $statement;

	public function __construct($period, $statement)
	{
		$this->period = $period;
		$this->statement = $statement;
	}
}

?>