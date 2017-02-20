<?php

namespace DotBlue\CodeSniffer\Helpers;

use PHP_CodeSniffer;
use Tester\Assert;


class NegativeExpectation implements Expectation
{

	/** @var string */
	private $expectedMessage;

	/** @var int[] */
	private $expectedOnLines = [];

	/** @var bool */
	private $isFixable = FALSE;

	/** @var PHP_CodeSniffer */
	private $sniffer;

	/** @var TestedFile */
	private $testedFile;



	public function __construct($message, TestedFile $testedFile)
	{
		$this->expectedMessage = $message;
		$this->testedFile = $testedFile;
	}



	/**
	 * Expect an error message on given line
	 * @param  int
	 * @return $this
	 */
	public function onLine($line)
	{
		$this->expectedOnLines[] = $line;
		return $this;
	}



	/**
	 * Expect an error message on given lines
	 * @param  int []
	 * @return $this
	 */
	public function onLines(array $lines)
	{
		foreach ($lines as $line) {
			$this->expectedOnLines[] = $line;
		}
		return $this;
	}



	/**
	 * Set sniff as fixable. The .fixed variant of invalid file will be tested against valid file
	 * @return $this
	 */
	public function isFixable()
	{
		$this->isFixable = TRUE;
		return $this;
	}



	/**
	 * @param  PHP_CodeSniffer
	 */
	public function evaluate(PHP_CodeSniffer $sniffer)
	{
		$this->sniffer = $sniffer;
		$this->testValid();
		$this->testInvalid();

		if ($this->isFixable) {
			$this->testFix();
		}
	}



	/**
	 * @return TestedFile
	 */
	public function getFile()
	{
		return $this->testedFile;
	}



	private function testValid()
	{
		$file = $this->sniffer->processFile(Tester::$setup['invalidDir'] . $this->testedFile->getName() . '.php');
		$errors = $file->getErrors();
		Assert::false(empty($errors));
	}



	private function testInvalid()
	{
		$file = $this->sniffer->processFile(Tester::$setup['validDir'] . $this->testedFile->getName() . '.php');
		$errors = $file->getErrors();

		foreach ($this->expectedOnLines as $line) {
			$errorsOnLine = isset($errors[$line]) ? $errors[$line] : [];

			$errorFound = FALSE;
			foreach ($errorsOnLine as $error) {
				if ($error[0]['message'] === $this->expectedMessage) {
					$errorFound = TRUE;
					break;
				}
			}

			if ($errorFound) {
				Assert::fail('Message "' . $this->expectedMessage . '" found on line "' . $line . '" but was not expected.');
			}
		}

	}



	private function testFix()
	{
		exec(Tester::$setup['fixerPath'] . ' ' . Tester::$setup['invalidDir'] . $this->testedFile->getName() . '.php --standard=../ruleset.xml --suffix=.fixed', $out, $status);
		if ($status === 1) {
			$content = file_get_contents(Tester::$setup['invalidDir'] . $this->testedFile->getName() . '.php.fixed');
			Assert::matchFile(Tester::$setup['validDir'] . $this->testedFile->getName() . '.php', $content);
		}
	}

}
