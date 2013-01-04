<?php

namespace Mishak\SheetToData\Extractors;

class Column extends Base {


	private $extractors;

	public function __construct($name, $extractors)
	{
		parent::__construct($name);
		$this->extractors = $extractors;
	}


	public function extract(&$line)
	{
		try {
			$c = key($line);
			$data = $column = current($line);
			foreach ($this->extractors as $extractor) {
				if (is_array($data)) {
					array_walk($data, function (&$value) use ($extractor) {
						$value = $extractor->extract($value);
					});
				} else {
					$data = $extractor->extract($data);
				}
			}
			next($line);
			return array($this->getName() => $data);
		} catch (Value\InvalidValueException $e) {
			throw new InvalidException("Invalid column value '$column'.", 0, $e);
		}
	}

}
