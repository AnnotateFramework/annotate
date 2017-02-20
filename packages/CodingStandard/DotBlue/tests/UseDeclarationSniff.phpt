<?php

use DotBlue\CodeSniffer\Helpers\Tester;


require __DIR__ . '/bootstrap.php';

$tester = new Tester();
$tester->setFile('UseDeclaration')
	->setSniff('Namespaces.UseDeclaration')
	->expectMessage('There must be two blank lines after the last USE statement; 1 found;')
	->onLine(5)
	->isFixable();

$tester->test();
