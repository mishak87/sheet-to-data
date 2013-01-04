<?php

namespace Mishak\SheetToData\Extractors;

class Group extends Base {


	private $children;

	public function __construct($name, $children)
	{
		parent::__construct($name);
		$this->children = $children;
	}


	public function extract(&$line)
	{
		$data = array();
		foreach ($this->children as $child) {
			$data[] = $child->extract($line);
		}
		return array($this->getName() => call_user_func_array('array_merge_recursive', $data));
	}

}
