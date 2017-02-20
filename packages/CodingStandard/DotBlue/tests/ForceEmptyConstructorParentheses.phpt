<?php

use DotBlue\CodeSniffer\Helpers\Tester;


require __DIR__ . '/bootstrap.php';

$tester = new Tester();

$tester->setFile('ForceEmptyConstructorParentheses')
	->setSniff('Php.ForceEmptyConstructorParentheses')
	->expectMessage('There must be parentheses after constructor call.')
	->onLine(3)
	->isFixable();

$tester->setFile('ForceEmptyConstructorParenthesesNamespaced')
	->setSniff('Php.ForceEmptyConstructorParentheses')
	->expectMessage('There must be parentheses after constructor call.')
	->onLine(3)
	->isFixable();

$tester->setFile('ForceEmptyConstructorParenthesesInFunctionCall')
	->setSniff('Php.ForceEmptyConstructorParentheses')
	->expectMessage('There must be parentheses after constructor call.')
	->onLine(3)
	->isFixable();

$tester->setFile('ForceEmptyConstructorParenthesesInArray')
	->setSniff('Php.ForceEmptyConstructorParentheses')
	->expectMessage('There must be parentheses after constructor call.')
	->onLine(4)
	->isFixable();

$tester->test();
