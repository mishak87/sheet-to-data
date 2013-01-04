<?php

namespace Mishak\SheetToData\Definition;

use Mishak\SheetToData\Extractors,
	Mishak\SheetToData\Extractors\Value,
	ReflectionClass;



class XmlReader
{
	private $counter;
	private $refs;


	public function fromString($contents)
	{
		$this->reset();
		return $this->parseExtractor(simplexml_load_string($contents));
	}



	public function fromFile($filename)
	{
		$this->reset();
		return $this->parseExtractor(simplexml_load_file($filename));
	}



	private function reset()
	{
		$this->counter = 0;
		$this->refs = array();
	}




	private function parseExtractor($node)
	{
		static $map = array(
			'empty' => 'blank'
		);
		$tagName = $node->getName();
		switch ($tagName) {
			case 'col':
			case 'column':
				$values = array();
				foreach ($node->children() as $child) {
					$values[] = $this->parseValue($child);
				}
				if (!$values) {
					$values = array(new Value\Text);
				}
				$name = $this->name($node);
				return $this->refs[$name] = new Extractors\Column($name, $values);

			case 'ref':
			case 'reference':
				if (isset($node['name'])) {
					return $this->reference((string) $node['name']);
				} else {
					throw new \Exception("Reference must have attribute name.");
				}

			case 'template':
				$this->children($node);
				break;

			default:
				$className = ucfirst(isset($map[$tagName]) ? $map[$tagName] : $tagName);
				$class = 'Mishak\\SheetToData\\Extractors\\' . $className;
				if (!class_exists($class)) {
					throw new \Exception("Unknown class '$class'.");
				}
				$reflection = new ReflectionClass($class);
				$parameters = $reflection->getMethod('__construct')->getParameters();
				$args = array();
				foreach ($parameters as $parameter) {
					$name = $parameter->getName();
					if (in_array($name, array('name', 'child', 'children'))) {
						$args[$name] = $this->$name($node);
					} elseif (isset($node[$name])) {
						$value = (string) $node[$name];
						if (isset($value[0]) && $value[0] === '@') {
							$value = $this->reference(substr($value, 1));
						}
						$args[$name] = $value;
					} elseif ($parameter->isOptional()) {
						$args[$name] = $parameter->getDefaultValue();
					} else {
						throw new \Exception("Extractor '$tagName' must have attribute '$name'.");
					}
				}
				$extractor = $reflection->newInstanceArgs($args);
				if (isset($args['name'])) {
					$this->refs[(string) $args['name']] = $extractor;
				}
				return $extractor;
		}
	}



	private function reference($name)
	{
		if (isset($this->refs[$name])) {
			return $this->refs[$name];
		} else {
			throw new \Exception("Unknown reference '$name'.");
		}
	}



	private function children($node)
	{
		$children = array();
		foreach ($node->children() as $child) {
			if (NULL !== $extractor = $this->parseExtractor($child)) {
				$children[] = $extractor;
			}
		}
		return $children;
	}



	private function child($node)
	{
		$children = $this->children($node);
		if (count($children) == 1) {
			return reset($children);
		} else {
			throw new \Exception("Extractor '{$node->getName()}' must have exactly one child");
		}
	}


	private function parseValue($node)
	{
		if (count($node)) {
			throw new \Exception("Value nodes cannot have children nodes.");
		}

		static $map = array(
			'empty' => 'blank'
		);

		$tagName = $node->getName();
		$className = ucfirst(isset($map[$tagName]) ? $map[$tagName] : $tagName);
		$class = 'Mishak\\SheetToData\\Extractors\\Value\\' . $className;
		if (!class_exists($class)) {
			throw new \Exception("Unknown class '$class'.");
		}
		$reflection = new ReflectionClass($class);
		if (!$reflection->hasMethod('__construct')) {
			return new $class;
		}
		$parameters = $reflection->getMethod('__construct')->getParameters();
		$args = array();
		foreach ($parameters as $parameter) {
			$name = $parameter->getName();
			if (isset($node[$name])) {
				$args[$name] = (string) $node[$name];
			} elseif ($parameter->isOptional()) {
				$args[$name] = $parameter->getDefaultValue();
			} else {
				throw new \Exception("Value extractor '$tagName' must have attribute '$name'.");
			}
		}
		return $reflection->newInstanceArgs($args);
	}



	private function name($node)
	{
		if (isset($node['name'])) {
			return (string) $node['name'];
		} else {
			return '#' . ++$this->counter;
		}
	}

}