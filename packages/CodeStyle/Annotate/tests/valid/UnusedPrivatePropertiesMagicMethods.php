<?php

class Coordinates
{

	private $x;



	public function __get($name)
	{
		return $this->$name;
	}
}
