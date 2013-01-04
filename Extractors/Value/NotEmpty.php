<?php

namespace Mishak\SheetToData\Extractors\Value;

class NotEmpty
{

	public function extract($value)
	{
		if ('' === $value || NULL === $value) {
			throw new InvalidValueException("Value cannot be empty.");
		}
		return $value;
	}

}
