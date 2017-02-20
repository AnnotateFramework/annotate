<?php

use DotBlue\CodeSniffer\Helpers\Tester;


require __DIR__ . '/bootstrap.php';

$tester = new Tester();
$tester->setFile('NamespaceDeclarationWithoutUseStatement')
	->setSniff('Namespaces.NamespaceDeclaration')
	->expectMessage('There must be two blank lines after the namespace declaration. In case there is no use statement. Found 1')
	->onLine(3)
	->isFixable();

$tester->setFile('NamespaceDeclarationWithUseStatement')
	->setSniff('Namespaces.NamespaceDeclaration')
	->expectMessage('There must be one blank line after the namespace declaration in case use statement follows. Found 2')
	->onLine(3)
	->isFixable();

$tester->test();
