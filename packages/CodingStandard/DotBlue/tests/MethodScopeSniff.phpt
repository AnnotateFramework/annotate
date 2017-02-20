<?php

use DotBlue\CodeSniffer\Helpers\Tester;


require __DIR__ . '/bootstrap.php';

$tester = new Tester();
$tester->setFile('MethodVisibilityInClass')
	->setSniff('Scope.MethodScope')
	->expectMessage('Visibility must be declared on method "foo"')
	->onLine(9)
	->isFixable();

$tester->setFile('MethodVisibilityInInterface')
	->setSniff('Scope.MethodScope')
	->expectMessage('Visibility must not be declared on method "foo" in interface')
	->onLine(9)
	->isFixable();

$tester->test();
