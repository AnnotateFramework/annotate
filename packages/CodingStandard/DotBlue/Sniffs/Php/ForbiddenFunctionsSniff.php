<?php

namespace DotBlue\Sniffs\Php;

use Squiz_Sniffs_PHP_ForbiddenFunctionsSniff;


class ForbiddenFunctionsSniff extends Squiz_Sniffs_PHP_ForbiddenFunctionsSniff
{

	public $forbiddenFunctions = [
		'd' => NULL,
		'dump' => NULL,
		'var_dump' => NULL,
	];

}
