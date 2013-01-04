<?php

namespace Mishak\SheetToData\Extractors;

class Skip extends Base {

	private $times;

	public function __construct($name = '', $times = 1)
	{
		parent::__construct($name);
		$this->times = $times;
	}

	public function extract(&$line)
	{
		for ($i = 0; $i < $this->times; ++$i) {
			if (NULL === key($line)) {
				throw new InvalidException("Cannot skip past end of line.");
			}
			next($line);
		}
		return array($this->name, NULL);
	}

}
