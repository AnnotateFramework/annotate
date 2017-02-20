<?php

use DotBlue\CodeSniffer\Helpers\Tester;


require __DIR__ . '/bootstrap.php';

$tester = new Tester();
$tester->setFile('AbsoluteNamespaceUsage')
	->setSniff('Php.AbsoluteNamespaceUsage')
	->expectMessage('Using absolute namespaces if forbidden. Import class \'\\StdClass\' with use statement.')
	->onLine(9)
	->getFile()
	->expectMessage('Using absolute namespaces if forbidden. Import class \'\\Nette\\Utils\\Strings\' with use statement.')
	->onLine(16);

$tester->test();
