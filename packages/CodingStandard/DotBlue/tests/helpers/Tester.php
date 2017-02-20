<?php

namespace DotBlue\CodeSniffer\Helpers;


use Exception;
use PHP_CodeSniffer;


class Tester
{

	public static $setup = [];

	/** @var TestedFile[] */
	private $testedFiles = [];



	/**
	 * @param  array
	 */
	public static function setup($setup)
	{
		self::$setup = $setup;
	}



	/**
	 * @param  string
	 * @return TestedFile
	 */
	public function setFile($file)
	{
		$testedFile = new TestedFile($file);
		$this->testedFiles[] = $testedFile;
		return $testedFile;
	}



	public function test()
	{
		define('PHP_CODESNIFFER_IN_TESTS', TRUE);
		define('PHP_CODESNIFFER_CBF', TRUE);

		foreach ($this->testedFiles as $testedFile) {
			$sniffer = new PHP_CodeSniffer();
			$sniffer->processRuleset(self::$setup['ruleset']);
			if (!$testedFile->getSniff()) {
				throw new Exception('Sniff file not set. Please set sniff by using ' . TestedFile::class . '::setSniff($sniff) method.');
			}
			$sniffer->registerSniffs([
				Tester::$setup['sniffsDir'] . '/' . str_replace('.', '/', $testedFile->getSniff()) . 'Sniff.php',
			], []);
			$sniffer->populateTokenListeners();
			$testedFile->evaluate($sniffer);
		}
	}

}
