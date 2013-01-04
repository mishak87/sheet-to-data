<?php

namespace Mishak\SheetToData\Extractors;

class LoopUntil extends Base {

	private $child;

	private $stop;



	public function __construct($name, $child, $stop)
	{
		parent::__construct($name);
		$this->child = $child;
		$this->stop = $stop;
	}



	public function extract(&$line)
	{
		$items = array();
		$count = 0;
		$this->capture($line);
		while (NULL !== key($line)) {
			try {
				$this->capture($line, 'test');
				$this->stop->extract($line);
				$this->rollback($line, 'test');
				break;
			} catch (InvalidException $e) {
				$this->rollback($line, 'test');
			}

			try {
				$data = $this->child->extract($line);
				$items[] = $data[$this->child->getName()];
			} catch (InvalidException $e) {
				$this->rollback($line);
				throw new InvalidException("Invalid child.");
			}
		}
		return array($this->getName() => $items);
	}

}
