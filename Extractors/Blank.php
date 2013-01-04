<?php

namespace Mishak\SheetToData\Extractors;

class Blank extends Base
{
	private $child;

	public function __construct()
	{
		$this->child = new Column('__empty__', new Value\Text);
		parent::__construct($this->child->getName());
	}


	public function extract(&$line)
	{
		$extracted = $this->child->extract($line);
		if ($this->isEmpty($extracted[$this->child->getName()])) {
			return $extracted;
		} else {
			throw new InvalidException("Not empty.");
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
