<?php

namespace DotBlue\Sniffs\Namespaces;

use PHP_CodeSniffer_File;
use PSR2_Sniffs_Namespaces_NamespaceDeclarationSniff;


class NamespaceDeclarationSniff extends PSR2_Sniffs_Namespaces_NamespaceDeclarationSniff
{

	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
	{
		$tokens = $phpcsFile->getTokens();

		for ($i = ($stackPtr + 1); $i < ($phpcsFile->numTokens - 1); $i++) {
			if ($tokens[$i]['line'] === $tokens[$stackPtr]['line']) {
				continue;
			}

			break;
		}

		// The $i var now points to the first token on the line after the
		// namespace declaration, which must be a blank line.
		$next = $phpcsFile->findNext(T_WHITESPACE, $i, $phpcsFile->numTokens, TRUE);
		if ($next === FALSE) {
			return;
		}

		$diff = ($tokens[$next]['line'] - $tokens[$i]['line']);

		$useStatement = $phpcsFile->findNext(T_USE, $i, $phpcsFile->findNext([
			T_CLASS,
			T_INTERFACE,
			T_TRAIT,
		], $i, $phpcsFile->numTokens));

		if ($useStatement === FALSE) {
			if ($diff !== 2) {
				$error = 'There must be two blank lines after the namespace declaration. In case there is no use statement. Found %s';
			}
		} else {
			if ($diff !== 1) {
				$error = 'There must be one blank line after the namespace declaration in case use statement follows. Found %s';
			}
		}

		if (!isset($error)) {
			return;
		}


		if ($diff < 0) {
			$diff = 0;
		}

		$fix = $phpcsFile->addFixableError($error, $stackPtr, 'BlankLineAfter', [$diff]);

		if ($fix === TRUE) {
			if ($diff === 0) {
				$phpcsFile->fixer->addNewlineBefore($i);
			} else {
				$phpcsFile->fixer->beginChangeset();
				for ($x = $i; $x < $next; $x++) {
					if ($tokens[$x]['line'] === $tokens[$next]['line']) {
						break;
					}

					$phpcsFile->fixer->replaceToken($x, '');
				}

				$phpcsFile->fixer->addNewline($i);

				if (!$useStatement) {
					$phpcsFile->fixer->addNewline($i);
				}

				$phpcsFile->fixer->endChangeset();
			}
		}
	}

}
