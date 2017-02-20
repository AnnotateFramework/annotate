<?php

namespace DotBlue\Sniffs\Namespaces;

use PHP_CodeSniffer_File;
use PSR2_Sniffs_Namespaces_UseDeclarationSniff;


class UseDeclarationSniff extends PSR2_Sniffs_Namespaces_UseDeclarationSniff
{

	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
	{
		if ($this->shouldIgnoreUse($phpcsFile, $stackPtr) === TRUE) {
			return;
		}

		$tokens = $phpcsFile->getTokens();

		// One space after the use keyword.
		if ($tokens[($stackPtr + 1)]['content'] !== ' ') {
			$error = 'There must be a single space after the USE keyword';
			$fix = $phpcsFile->addFixableError($error, $stackPtr, 'SpaceAfterUse');
			if ($fix === TRUE) {
				$phpcsFile->fixer->replaceToken(($stackPtr + 1), ' ');
			}
		}

		// Only one USE declaration allowed per statement.
		$next = $phpcsFile->findNext([T_COMMA, T_SEMICOLON], ($stackPtr + 1));
		if ($tokens[$next]['code'] === T_COMMA) {
			$error = 'There must be one USE keyword per declaration';
			$fix = $phpcsFile->addFixableError($error, $stackPtr, 'MultipleDeclarations');
			if ($fix === TRUE) {
				$phpcsFile->fixer->replaceToken($next, ';' . $phpcsFile->eolChar . 'use ');
			}
		}

		// Make sure this USE comes after the first namespace declaration.
		$prev = $phpcsFile->findPrevious(T_NAMESPACE, ($stackPtr - 1));
		if ($prev !== FALSE) {
			$first = $phpcsFile->findNext(T_NAMESPACE, 1);
			if ($prev !== $first) {
				$error = 'USE declarations must go after the first namespace declaration';
				$phpcsFile->addError($error, $stackPtr, 'UseAfterNamespace');
			}
		}

		// Only interested in the last USE statement from here onwards.
		$nextUse = $phpcsFile->findNext(T_USE, ($stackPtr + 1));
		while ($this->shouldIgnoreUse($phpcsFile, $nextUse) === TRUE) {
			$nextUse = $phpcsFile->findNext(T_USE, ($nextUse + 1));
			if ($nextUse === FALSE) {
				break;
			}
		}

		if ($nextUse !== FALSE) {
			return;
		}

		$end = $phpcsFile->findNext(T_SEMICOLON, ($stackPtr + 1));
		$next = $phpcsFile->findNext(T_WHITESPACE, ($end + 1), NULL, TRUE);
		$diff = ($tokens[$next]['line'] - $tokens[$end]['line'] - 1);
		if ($diff !== 2) {
			if ($diff < 0) {
				$diff = 0;
			}

			$error = 'There must be two blank lines after the last USE statement; %s found;';
			$data = [$diff];
			$fix = $phpcsFile->addFixableError($error, $stackPtr, 'SpaceAfterLastUse', $data);
			if ($fix === TRUE) {
				if ($diff === 0) {
					$phpcsFile->fixer->addNewline($end);
				} else {
					$phpcsFile->fixer->beginChangeset();
					for ($i = ($end + 1); $i < $next; $i++) {
						if ($tokens[$i]['line'] === $tokens[$next]['line']) {
							break;
						}

						$phpcsFile->fixer->replaceToken($i, '');
					}

					$phpcsFile->fixer->addNewline($end);
					$phpcsFile->fixer->addNewline($end);
					$phpcsFile->fixer->addNewline($end);
					$phpcsFile->fixer->endChangeset();
				}
			}
		}
	}



	private function shouldIgnoreUse(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
	{
		$tokens = $phpcsFile->getTokens();

		// Ignore USE keywords inside closures.
		$next = $phpcsFile->findNext(T_WHITESPACE, ($stackPtr + 1), NULL, TRUE);
		if ($tokens[$next]['code'] === T_OPEN_PARENTHESIS) {
			return TRUE;
		}

		// Ignore USE keywords for traits.
		if ($phpcsFile->hasCondition($stackPtr, [T_CLASS, T_TRAIT]) === TRUE) {
			return TRUE;
		}

		return FALSE;

	}

}
