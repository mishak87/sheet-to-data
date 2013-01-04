<?php

namespace Mishak\SheetToData\Extractors;

class NotEmpty extends Base {

	private $child;

	private $minValues;


	public function __construct($child, $minValues = 1)
	{
		parent::__construct($child->getName());
		$this->child = $child;
		$this->minValues = $minValues;
	}


	public function extract(&$line)
	{
		$this->capture($line);
		$extracted = $this->child->extract($line);
		if (!$this->isEmpty($extracted[$this->child->getName()])) {
			return $extracted;
		} else {
			$this->rollback($line);
			throw new InvalidException("Empty.");
		}
	}

	private function isEmpty($value)
	{
		if (is_array($value)) {
			$count = 0;
			foreach ($value as $_value) {
				if (!$this->isEmpty($_value)) {
					++$count;
				}
			}
			return $count >= $this->minValues;
		} else {
			return $value === '' || NULL === $value;
		}
	}

}
