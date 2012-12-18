<?php

namespace Mishak\SheetToData;

use Nette;

class Sheet extends Nette\Object {


	private $rows;

	public function __construct($rows)
	{
		$this->rows = $rows;
	}


	public function apply(IFilter $filter)
	{
		$this->rows = $filter->filter($this->rows);
	}


	public function getRows()
	{
		return $this->rows;
	}


	public static function fromCsv($filename, $delimiter = ',', $enclosure = '"', $escape = '\\')
	{
		$rows = array();
		$fp = fopen($filename, 'rb');
		while (FALSE !== ($row = fgetcsv($fp, 0, $delimiter, $enclosure, $escape))) {
			$rows[] = $row;
		}
		fclose($fp);
		return new static($rows);
	}


	public function dumpHtml()
	{
		echo '<table border="1">';
		foreach ($this->rows as $row) {
			echo '<tr>';
			foreach ($row as $col) {
				echo '<td>', htmlSpecialChars($col), '</td>';
			}
			echo '</tr>';
		}
		echo '</table>';
	}

}
