<?php

namespace Mishak\SheetToData\Extractors\Value;

class RegExp {

	private $expression;



	public function __construct($expression)
	{
		$this->expression = $expression;
	}



	public function extract($value)
	{
		if (preg_match_all($this->expression, $value, $matches)) {
			return array(
				'value' => $value,
				'matches' => $matches,
			);
		} else {
			throw new InvalidValueException("Value '$value' is not matching {$this->expression}.");
		}
	}

}
