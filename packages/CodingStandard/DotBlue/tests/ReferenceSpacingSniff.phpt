<?php

use DotBlue\CodeSniffer\Helpers\Tester;


require __DIR__ . '/bootstrap.php';

$tester = new Tester();
$tester->setFile('ReferenceSpacing')
	->setSniff('WhiteSpace.ReferenceSpacing')
		->expectMessage('There must be exactly one space between & and variable. Found 0')
			->onLine(4)
			->isFixable()
	->getFile()
		->expectMessage('There must be exactly one space between & and variable. Found 2')
			->onLine(5)
			->isFixable();
$tester->test();
