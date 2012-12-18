<?php

namespace Mishak\SheetToData\Extractors;

class Skip extends Base {

	private $skip;

	public function __construct($name = '', $skip = 1)
	{
		parent::__construct($name);
		$this->skip = $skip;
	}

	public function extract(&$line)
	{
		for ($i = 0; $i < $this->skip; ++$i) {
			if (NULL === key($line)) {
				throw new \Exception("Cannot skip past end of line.");
			}
			next($line);
		}
		return array($this->name, NULL);
	}

}
