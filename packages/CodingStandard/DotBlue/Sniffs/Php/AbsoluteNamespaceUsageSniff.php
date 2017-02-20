<?php

namespace DotBlue\Sniffs\Php;

use PHP_CodeSniffer_File;
use PHP_CodeSniffer_Sniff;


class AbsoluteNamespaceUsageSniff implements PHP_CodeSniffer_Sniff
{

	public function register()
	{
		return [
			T_NS_SEPARATOR,
		];
	}



	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
	{
		$tokens = $phpcsFile->getTokens();

		if ($tokens[$stackPtr - 1]['code'] !== T_STRING) {
			$namespace = '';
			$ptr = $stackPtr;
			do {
				$token = $tokens[$ptr++];
				$namespace .= $token['content'];
			} while (in_array($token['code'], [
				T_NS_SEPARATOR,
				T_STRING,
			]));
			$namespace = trim($namespace);

			$phpcsFile->addError('Using absolute namespaces if forbidden. Import class \'' . $namespace . '\' with use statement.', $stackPtr);
		}
	}

}
