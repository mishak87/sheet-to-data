<?php

namespace Mishak\SheetToData\Filter;

class ClipEmptyRowsAndCols implements \Mishak\SheetToData\IFilter
{


	private $justBorder;

	public function __construct($justBorder = TRUE)
	{
		$this->justBorder = $justBorder;
	}


	public function filter($table)
	{
		$emptyRows = array();
		$emptyCols = array_fill_keys(range(0, count($table[0]) - 1), TRUE);
		foreach ($table as $indexRow => $row) {
			$emptyRow = TRUE;
			foreach ($row as $colIndex => $value) {
				$empty = trim($value) === '';
				$emptyRow = $emptyRow && $empty;
				$emptyCols[$colIndex] = $emptyCols[$colIndex] && $empty;
			}
			$emptyRows[$indexRow] = $emptyRow;
		}
		if ($this->setIndexesFromEmpty($emptyRows)) {
			$this->remove($table);
		}
		if ($this->setIndexesFromEmpty($emptyCols)) {
			foreach ($table as &$row) {
				$this->remove($row);
			}
		}
		return array_values($table);
	}


	/**
	 * Sets indexes from empty values and filters them according to justBorder option.
	 *
	 * @param array $empty of bool values regarding emptines for each row line
	 * @return bool TRUE if there is something to remove
	 */
	private function setIndexesFromEmpty($empty)
	{
		$size = count($empty);
		$empty = array_filter($empty);
		if ($this->justBorder) {
			$this->indexes = array();
			$len = count($empty);
			// 0, 1, 2 <break> 5
			for ($i = 0; $i < $size; ++$i) {
				if (isset($empty[$i])) {
					$this->indexes[] = $i;
				} else {
					break;
				}
			}
			// 9, 8, <break> 4
			for ($i = $size - 1; $i > 0; --$i) {
				if (isset($empty[$i])) {
					$this->indexes[] = $i;
				} else {
					break;
				}
			}
		} else {
			$this->indexes = array_values($empty);
		}
		return (bool) $this->indexes;
	}


	/**
	 * Removes items from array specified by $this->indexes
	 *
	 * @param array &$lines
	 */
	private function remove(&$lines)
	{
		foreach ($this->indexes as $index) {
			unset($lines[$index]);
		}
	}

}
