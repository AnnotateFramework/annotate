<?php

use DotBlue\CodeSniffer\Helpers\Tester;


require __DIR__ . '/bootstrap.php';

$tester = new Tester();
$tester->setFile('UnusedPrivateProperties')
	->setSniff('Php.UnusedPrivateProperties')
	->expectMessage('Found unused private property \'$bar\'. You should remove it.')
	->onLine(11);

$tester->setFile('UnusedPrivatePropertiesWithAliasedThis')
	->setSniff('Php.UnusedPrivateProperties')
	->expectMessage('Found unused private property \'$bar\'. You should remove it.')
	->onLine(11);

$tester->setFile('UnusedPrivateStaticProperties')
	->setSniff('Php.UnusedPrivateProperties')
	->expectMessage('Found unused private static property \'$bar\'. You should remove it.')
	->onLine(11);

$tester->setFile('UnusedPrivatePropertiesMagicMethods')
	->setSniff('Php.UnusedPrivateProperties')
	->doNotExpectMessage('Found unused private property \'$x\'. You should remove it.')
	->onLine(6)
	->getFile()
	->expectMessage('Found unused private property \'$x\'. You should remove it.')
	->onLine(6);

$tester->test();
