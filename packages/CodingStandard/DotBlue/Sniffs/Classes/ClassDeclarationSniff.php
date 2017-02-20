<?php

namespace DotBlue\Sniffs\Classes;

use PHP_CodeSniffer_File;
use PHP_CodeSniffer_Sniff;


class ClassDeclarationSniff implements PHP_CodeSniffer_Sniff
{

	public function register()
	{
		return [
			T_CLASS,
			T_TRAIT,
			T_INTERFACE,
		];
	}



	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
	{
		$tokens = $phpcsFile->getTokens();
		$token = $tokens[$stackPtr];
		$opener = $tokens[$token['scope_opener']];
		$closer = $tokens[$token['scope_closer']];

		if ($tokens[$token['scope_opener']]['line'] === $tokens[$token['scope_closer']]['line']) {
			if ($tokens[$token['scope_opener']]['line'] !== $tokens[$stackPtr]['line']) {
 				$phpcsFile->addError('Both opening and closing brace must be on same line as declaration in case of empty body.', $token['scope_opener']);
			}

			return;
		}

		if ($opener) {
			$this->processOpen($phpcsFile, $token['scope_opener']);
		}

		if ($closer) {
			$this->processClose($phpcsFile, $token['scope_closer']);
		}
	}



	private function processOpen(PHP_CodeSniffer_File $phpcsFile, $opener)
	{
		$tokens = $phpcsFile->getTokens();
		$nextContent = $phpcsFile->findNext(T_WHITESPACE, ($opener + 1), NULL, TRUE);
		$diff = $tokens[$nextContent]['line'] - $tokens[$opener]['line'];

		if ($diff !== 2) {
			$fix = $phpcsFile->addFixableError('There must be one empty line before the class body. Found ' . ($diff - 1), $opener);

			if ($fix) {
				$phpcsFile->fixer->beginChangeset();
				$currentDiff = $diff;
				if ($diff < 2) {
					while ($currentDiff < 2) {
						$phpcsFile->fixer->addNewline($opener);
						$currentDiff++;
					}
				}
				if ($diff > 2) {
					while ($currentDiff > 2) {
						$phpcsFile->fixer->replaceToken($opener + 1, '');
						$currentDiff--;
					}
				}
				$phpcsFile->fixer->endChangeset();
			}
		}
	}



	private function processClose(PHP_CodeSniffer_File $phpcsFile, $closer)
	{
		$tokens = $phpcsFile->getTokens();
		$prevContent = $phpcsFile->findPrevious(T_WHITESPACE, ($closer - 1), NULL, TRUE);
		$diff = $tokens[$closer]['line'] - $tokens[$prevContent]['line'];

		if ($diff !== 2) {
			$fix = $phpcsFile->addFixableError('There must be one empty line after the body. Found ' . ($diff - 1), $closer);

			if ($fix) {
				$phpcsFile->fixer->beginChangeset();
				$currentDiff = $diff;
				if ($diff < 2) {
					while ($currentDiff < 2) {
						$phpcsFile->fixer->addNewlineBefore($closer);
						$currentDiff++;
					}
				}
				if ($diff > 2) {
					while ($currentDiff > 2) {
						$phpcsFile->fixer->replaceToken($closer - 1, '');
						$currentDiff--;
					}
				}
				$phpcsFile->fixer->endChangeset();
			}
		}
	}

}
