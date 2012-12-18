<?php

namespace Mishak\SheetToData\Extractors\Value;

class Items {


	private $separator;

	/**
	 * Extracts from value <begin>$separator<end>
	 */
	public function __construct($separator)
	{
		$this->separator = $separator;
	}


	public function extract($value)
	{
		$items = explode($this->separator, (string) $value);
		return $items;
	}

}
