<?php

namespace Mishak\SheetToData\Extractors;

abstract class Base {


	protected $name;

	public function __construct($name)
	{
		$this->name = $name;
	}


	public function getName()
	{
		return $this->name;
	}


	private $capture = array();

	protected function capture(&$line, $name = NULL)
	{
		$this->capture[$name] = key($line);
	}


	protected function rollback(&$line, $name = NULL)
	{
		if (isset($this->capture[$name])) {
			while ($this->capture[$name] !== key($line)) {
				prev($line);
			}
		}
	}

}
