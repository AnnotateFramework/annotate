<?php

namespace DotBlue\CodeSniffer\Helpers;

use PHP_CodeSniffer;


class TestedFile
{

	/** @var Expectation[] */
	private $expectations = [];

	/** @var string */
	private $file;

	/** @var string */
	private $sniff;



	public function __construct($file)
	{
		$this->file = $file;
	}



	/**
	 * @param  string
	 * @return Expectation
	 */
	public function expectMessage($message)
	{
		$expectation = new PositiveExpectation($message, $this);
		$this->expectations[] = $expectation;
		return $expectation;
	}



	/**
	 * @param  string
	 * @return NegativeExpectation
	 */
	public function doNotExpectMessage($message)
	{
		$expectation = new NegativeExpectation($message, $this);
		$this->expectations[] = $expectation;
		return $expectation;
	}



	/**
	 * @param  PHP_CodeSniffer
	 */
	public function evaluate(PHP_CodeSniffer $sniffer)
	{
		foreach ($this->expectations as $expectation) {
			$expectation->evaluate($sniffer);
		}
	}



	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->file;
	}



	/**
	 * @return string
	 */
	public function getSniff()
	{
		return $this->sniff;
	}



	/**
	 * @param  string
	 * @return $this
	 */
	public function setSniff($sniff)
	{
		$this->sniff = $sniff;
		return $this;
	}

}
