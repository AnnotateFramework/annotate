<?php

use DotBlue\CodeSniffer\Helpers\Tester;


require __DIR__ . '/bootstrap.php';

$tester = new Tester();
$tester->setFile('BoolNaming')
	->setSniff('Conventions.BoolNaming')
	->expectMessage('Usage of "boolean" is forbidden. Use "bool" instead.')
	->onLines([10, 20, 24])
	->isFixable();

$tester->test();
