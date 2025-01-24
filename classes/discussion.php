<?php
class discussion
{
	public $heading;
	public $statement;

	public function __construct($heading, $statement)
	{
		$this->heading = $heading;
		$this->statement = $statement;
	}
}

?>