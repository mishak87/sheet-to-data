<?php

namespace Mishak\SheetToData\Extractors\Value;

use DateTime as DT;

class DateTime {


	private $format;

	public function __construct($format)
	{
		$this->format = $format;
	}


	public function extract($value)
	{
		$datetime = DT::createFromFormat($this->format, $value);
		if (FALSE === $datetime) {
			throw new InvalidValueException("Unsupported datetime: '$value' (format: '{$this->format}'");
		}
		return $datetime;
	}

}
