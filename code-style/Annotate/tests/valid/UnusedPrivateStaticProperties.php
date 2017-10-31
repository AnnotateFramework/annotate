<?php

namespace Foo;


class Foo
{

	private static $foo;

	private static $bar;

	public function foo()
	{
		self::$foo = 'bar';
	}



	private static function bar()
	{
		Foo::$bar = NULL;
	}

}
