<?php

class trafficItem
{
	public $title;
	public $description;
	public $link;
	public $date;
	public $jamfactor;

	static function compareTrafficItem($a, $b)
	{
		$jam1 = (int)$a->jamfactor;
		$jam2 = (int)$b->jamfactor;

		if ($jam1 == $jam2)
		{
			return 0;
		}

		// use reverse sort order so higher jam factors rise to the top
		return ($jam1 > $jam2) ? -1 : 1;
	}
}

?>