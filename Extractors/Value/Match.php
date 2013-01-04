<?php

namespace Mishak\SheetToData\Extractors\Value;

class Match {

	private $value;



	public function __construct($value)
	{
		$this->value = $value;
	}



	public function extract($value)
	{
		if ($value === $this->value) {
			return $value;
		} else {
			throw new InvalidValueException("Value '$value' is not matching '$this->value'.");
		}
	}

}
