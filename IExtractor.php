<?php

namespace Mishak\SheetToData;

interface IExtractor {

	/**
	 * Extract data from line (table or row)
	 *
	 * @param array[] $line
	 */

	public function extract($line);

}
