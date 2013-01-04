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
			// $rolls = 0;
			// $k = key($line);
			reset($line);
			while ($this->capture[$name] !== key($line)) {
				// if (++$rolls > 1000) {
				//	dump([$this->capture, $k, key($line)]);
				//	$debug();
				// }
				next($line);
			}
		}
	}

}
