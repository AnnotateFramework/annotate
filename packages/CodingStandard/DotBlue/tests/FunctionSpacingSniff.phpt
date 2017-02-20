<?php

use DotBlue\CodeSniffer\Helpers\Tester;


require __DIR__ . '/bootstrap.php';

$tester = new Tester();
$tester->setFile('FunctionSpacing')
	->setSniff('WhiteSpace.FunctionSpacing')
	->expectMessage('Expected 3 blank lines after function; 1 found')
	->onLine(12)
	->isFixable();

$tester->test();
