<?php

namespace Mishak\SheetToData;

use ArrayAccess;

class Grouper {

	private $key;

	private $group;

	public function __construct($key, $group)
	{
		$this->key = $key;
		$this->group = $group;
	}


	public function group($rows)
	{
		$indexes = array();
		$result = array();
		foreach ($rows as $row) {
			$key = $row[$this->key];
			if (!isset($indexes[$key])) {
				$index = $indexes[$key] = count($result);
				$resultRow = $row;
				foreach ($this->group as $key) {
					$resultRow[$key] = array();
				}
				$result[] = $resultRow;
			} else {
				$index = $indexes[$key];
			}
			foreach ($this->group as $group) {
				$result[$index][$group][] = $row[$group];
			}
		}
		return $result;
	}

}
