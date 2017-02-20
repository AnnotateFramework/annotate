<?php

namespace DotBlue\Sniffs\WhiteSpace;

use PHP_CodeSniffer_File;
use Squiz_Sniffs_WhiteSpace_FunctionSpacingSniff;


class FunctionSpacingSniff extends Squiz_Sniffs_WhiteSpace_FunctionSpacingSniff
{

	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
	{
		$tokens = $phpcsFile->getTokens();
		$this->spacing = (int) $this->spacing;

		/*
			Check the number of blank lines
			after the function.
		*/

		if (isset($tokens[$stackPtr]['scope_closer']) === FALSE) {
			// Must be an interface method, so the closer is the semicolon.
			$closer = $phpcsFile->findNext(T_SEMICOLON, $stackPtr);
		} else {
			$closer = $tokens[$stackPtr]['scope_closer'];
		}

		// Allow for comments on the same line as the closer.
		for ($nextLineToken = ($closer + 1); $nextLineToken < $phpcsFile->numTokens; $nextLineToken++) {
			if ($tokens[$nextLineToken]['line'] !== $tokens[$closer]['line']) {
				break;
			}
		}

		$foundLines = 0;
		if ($nextLineToken === ($phpcsFile->numTokens - 1)) {
			// We are at the end of the file.
			// Don't check spacing after the function because this
			// should be done by an EOF sniff.
			$foundLines = $this->spacing;
		} else {
			$nextContent = $phpcsFile->findNext(T_WHITESPACE, ($nextLineToken + 1), NULL, TRUE);
			if ($nextContent === FALSE) {
				// We are at the end of the file.
				// Don't check spacing after the function because this
				// should be done by an EOF sniff.
				$foundLines = $this->spacing;
			} else {
				$foundLines += ($tokens[$nextContent]['line'] - $tokens[$nextLineToken]['line']);
			}
		}


		if ($foundLines !== $this->spacing && $phpcsFile->findNext(T_FUNCTION, $closer) !== FALSE) {
			$error = 'Expected %s blank line';
			if ($this->spacing !== 1) {
				$error .= 's';
			}

			$error .= ' after function; %s found';
			$data = [
				$this->spacing,
				$foundLines,
			];

			$fix = $phpcsFile->addFixableError($error, $closer, 'After', $data);
			if ($fix === TRUE) {
				$phpcsFile->fixer->beginChangeset();
				for ($i = $nextLineToken; $i <= $nextContent; $i++) {
					if ($tokens[$i]['line'] === $tokens[$nextContent]['line']) {
						$phpcsFile->fixer->addContentBefore($i, str_repeat($phpcsFile->eolChar, $this->spacing));
						break;
					}

					$phpcsFile->fixer->replaceToken($i, '');
				}

				$phpcsFile->fixer->endChangeset();
			}
		}

		/*
			Check the number of blank lines
			before the function.
		*/

		$prevLineToken = NULL;
		for ($i = $stackPtr; $i > 0; $i--) {
			if (strpos($tokens[$i]['content'], $phpcsFile->eolChar) === FALSE) {
				continue;
			} else {
				$prevLineToken = $i;
				break;
			}
		}

		if (is_null($prevLineToken) === TRUE) {
			// Never found the previous line, which means
			// there are 0 blank lines before the function.
			$foundLines = 0;
			$prevContent = 0;
		} else {
			$currentLine = $tokens[$stackPtr]['line'];

			$prevContent = $phpcsFile->findPrevious(T_WHITESPACE, $prevLineToken, NULL, TRUE);
			if ($tokens[$prevContent]['code'] === T_DOC_COMMENT_CLOSE_TAG
				&& $tokens[$prevContent]['line'] === ($currentLine - 1)
			) {
				// Account for function comments.
				$prevContent = $phpcsFile->findPrevious(T_WHITESPACE, ($tokens[$prevContent]['comment_opener'] - 1), NULL, TRUE);
			}

			// Before we throw an error, check that we are not throwing an error
			// for another function. We don't want to error for no blank lines after
			// the previous function and no blank lines before this one as well.
			$prevLine = ($tokens[$prevContent]['line'] - 1);
			$i = ($stackPtr - 1);
			$foundLines = 0;
			while ($currentLine !== $prevLine && $currentLine > 1 && $i > 0) {
				if (isset($tokens[$i]['scope_condition']) === TRUE) {
					$scopeCondition = $tokens[$i]['scope_condition'];
					if ($tokens[$scopeCondition]['code'] === T_FUNCTION) {
						// Found a previous function.
						return;
					}
				} elseif ($tokens[$i]['code'] === T_FUNCTION) {
					// Found another interface function.
					return;
				}

				$currentLine = $tokens[$i]['line'];
				if ($currentLine === $prevLine) {
					break;
				}

				if ($tokens[($i - 1)]['line'] < $currentLine && $tokens[($i + 1)]['line'] > $currentLine) {
					// This token is on a line by itself. If it is whitespace, the line is empty.
					if ($tokens[$i]['code'] === T_WHITESPACE) {
						$foundLines++;
					}
				}

				$i--;
			}
		}


		if ($foundLines !== $this->spacing && $phpcsFile->findPrevious(T_FUNCTION, $stackPtr - $tokens[$stackPtr]['length']) !== FALSE) {
			$error = 'Expected %s blank line';
			if ($this->spacing !== 1) {
				$error .= 's';
			}

			$error .= ' before function; %s found';
			$data = [
				$this->spacing,
				$foundLines,
			];

			$fix = $phpcsFile->addFixableError($error, $stackPtr, 'Before', $data);
			if ($fix === TRUE) {
				if ($prevContent === 0) {
					$nextSpace = 0;
				} else {
					$nextSpace = $phpcsFile->findNext(T_WHITESPACE, ($prevContent + 1), $stackPtr);
					if ($nextSpace === FALSE) {
						$nextSpace = ($stackPtr - 1);
					}
				}

				if ($foundLines < $this->spacing) {
					$padding = str_repeat($phpcsFile->eolChar, ($this->spacing - $foundLines));
					$phpcsFile->fixer->addContent($nextSpace, $padding);
				} else {
					$nextContent = $phpcsFile->findNext(T_WHITESPACE, ($nextSpace + 1), NULL, TRUE);
					$phpcsFile->fixer->beginChangeset();
					for ($i = $nextSpace; $i < ($nextContent - 1); $i++) {
						$phpcsFile->fixer->replaceToken($i, '');
					}

					$phpcsFile->fixer->replaceToken($i, str_repeat($phpcsFile->eolChar, $this->spacing));
					$phpcsFile->fixer->endChangeset();
				}
			}
		}

	}

}
