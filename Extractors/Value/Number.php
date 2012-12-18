<?php

namespace Mishak\SheetToData\Extractors\Value;

class Number {


	public function extract($value)
	{
		if (!is_numeric($value)) {
			throw new InvalidValueException("Expected number '$value' given.");
		}
		return intval($value);
	}

}
