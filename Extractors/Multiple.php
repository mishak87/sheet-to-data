<?php

namespace Mishak\SheetToData\Extractors;

class Multiple extends Base {


	private $child;

	private $min;

	private $max;


	public function __construct($name, $child, $min = 1, $max = -1)
	{
		parent::__construct($name);
		$this->child = $child;
		$this->min = $min;
		$this->max = $max;
	}


	public function extract(&$line)
	{
		$items = array();
		$count = 0;
		$this->capture($line);
		while (NULL !== key($line) && ($this->max === -1 || $count < $this->max)) {
			$this->capture($line, 'loop');
			try {
				$data = $this->child->extract($line);
				$items[] = $data[$this->child->getName()];
			} catch (InvalidException $e) {
				$this->rollback($line, 'loop');
				break;
			}
			++$count;
		}

		if ($count < $this->min) {
			$this->rollback($line);
			throw new InvalidException("Multiple: Child '{$this->child->getName()}' must be repeated at least {$this->min} times.");
		}
		return array($this->getName() => $items);
	}

}
