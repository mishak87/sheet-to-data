<?php

namespace Mishak\SheetToData\Extractors;

class Row extends Base {


	private $columns;

	public function __construct($name, $columns)
	{
		parent::__construct($name);
		$this->columns = $columns;
	}


	public function extract(&$line)
	{
		$data = array();
		$row = current($line);
		foreach ($this->columns as $column) {
			$data[] = $column->extract($row);
		}
		next($line);
		return array($this->name => call_user_func_array('array_merge_recursive', $data));
	}

}
