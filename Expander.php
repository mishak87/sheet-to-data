<?php

namespace Mishak\SheetToData;

use ArrayAccess;

class Expander {

	private $key;

	public function __construct($keys)
	{
		$this->key = reset($keys);
	}


	public function expand($rows)
	{
		$result = array();
		foreach ($rows as $row) {
			$values = $row[$this->key];
			if (is_array($values) || $values instanceof ArrayAccess) {
				foreach ($values as $value) {
					$row[$this->key] = $value;
					$result[] = $row;
				}
			} else {
				$result[] = $row;
			}
		}
		return $result;
	}

}
