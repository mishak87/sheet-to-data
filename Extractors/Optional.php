<?php

namespace Mishak\SheetToData\Extractors;

class Optional extends Base {


	private $child;

	public function __construct($child)
	{
		parent::__construct(NULL);
		$this->child = $child;
	}


	public function extract(&$line)
	{
		try {
			$this->capture($line);
			if (NULL === key($line)) {
				throw new InvalidException('Already at the end no point in running child.');
			}
			return $this->child->extract($line);
		} catch (InvalidException $e) {
			$this->rollback($line);
			return array($this->child->getName() => NULL);
		}
	}

}
