<?php

namespace Mishak\SheetToData;

interface IFilter {

	/**
	 * Filter table of rows with cols of same width
	 *
	 * @param array[] $table
	 */

	public function filter($table);

}
