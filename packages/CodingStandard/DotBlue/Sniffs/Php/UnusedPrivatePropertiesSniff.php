<?php

namespace DotBlue\Sniffs\Php;

use PHP_CodeSniffer_File;
use PHP_CodeSniffer_Sniff;


class UnusedPrivatePropertiesSniff implements PHP_CodeSniffer_Sniff
{

	public function register()
	{
		return [
			T_PRIVATE,
		];
	}



	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
	{
		$tokens = $phpcsFile->getTokens();

		foreach ($tokens as $key => $token) {
			if ($token['code'] === T_FUNCTION) {
				if ($tokens[$key + 2]['content'] === '__get' || $tokens[$key + 2]['content'] === '__set') {
					return;
				}
			}
		}

		$classDeclaration = $phpcsFile->findNext(T_CLASS, 0);
		$className = $tokens[$classDeclaration + 2]['content'];

		$isStatic = $tokens[$stackPtr + 2]['code'] === T_STATIC;

		if ($tokens[$stackPtr + 2 + ($isStatic ? 2 : 0)]['code'] === T_FUNCTION) {
			return;
		}

		$propertyName = substr($tokens[$stackPtr + 2 + ($isStatic ? 2 : 0)]['content'], ($isStatic ? 0 : 1));
		$isUnused = TRUE;

		$thisAliases = ['$this'];

		if ($isStatic) {
			$thisAliases[] = 'self';
			$thisAliases[] = $className;
		}

		foreach ($tokens as $key => $token) {
			if ($token['code'] === T_EQUAL) {
				if (in_array($tokens[$key + 2]['content'], $thisAliases)) {
					$thisAliases[] = $tokens[$key - 2]['content'];
				}
			}
		}

		$checkOperator = $isStatic ? T_PAAMAYIM_NEKUDOTAYIM : T_OBJECT_OPERATOR;

		foreach ($tokens as $key => $token) {
			if ($token['code'] === $checkOperator && in_array($tokens[$key - 1]['content'], $thisAliases)) {
				if ($tokens[$key + 1]['content'] === $propertyName) {
					$isUnused = FALSE;
				}
			}
		}

		if ($isUnused) {
			if ($isStatic) {
				$phpcsFile->addError(sprintf('Found unused private static property \'%s\'. You should remove it.', $propertyName), $stackPtr);
			} else {
				$phpcsFile->addError(sprintf('Found unused private property \'$%s\'. You should remove it.', $propertyName), $stackPtr);
			}
		}
	}

}
