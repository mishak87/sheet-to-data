<?php

namespace Mishak\SheetToData\Extractors\Value;

class Range extends Items {


	public function extract($value)
	{
		$items = parent::extract($value);
		if ($items && 2 != ($count = count($items))) {
			throw new InvalidValueException("Range can only be of two items; $count given.");
		}
		return $items;
	}

}
