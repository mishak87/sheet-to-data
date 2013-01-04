<?php

namespace Mishak\SheetToData\Extractors\Value;

class Blank
{

	public function extract($value)
	{
		if ('' !== $value && NULL !== $value) {
			throw new InvalidValueException("Value must be empty.");
		}
		return $value;
	}

}
