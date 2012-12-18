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


	private $capture = NULL;

	protected function capture(&$line)
	{
		$this->capture = key($line);
	}


	protected function rollback(&$line)
	{
		if (NULL !== $this->capture) {
			while ($this->capture !== key($line)) {
				prev($line);
			}
		}
	}

}
