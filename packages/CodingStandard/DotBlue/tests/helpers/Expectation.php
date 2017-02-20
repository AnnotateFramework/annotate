<?php
namespace DotBlue\CodeSniffer\Helpers;

use PHP_CodeSniffer;


interface Expectation
{

	/**
	 * Expect an error message on given line
	 * @param  int
	 * @return $this
	 */
	function onLine($line);



	/**
	 * Expect an error message on given lines
	 * @param  int[]
	 * @return $this
	 */
	function onLines(array $lines);



	/**
	 * Set sniff as fixable. The .fixed variant of invalid file will be tested against valid file
	 * @return $this
	 */
	function isFixable();



	/**
	 * @param  PHP_CodeSniffer
	 */
	function evaluate(PHP_CodeSniffer $sniffer);



	/**
	 * @return TestedFile
	 */
	function getFile();

}
