<?php

namespace Foo;


class Foo
{

	private $foo;

	private $bar;

	public function foo()
	{
		$that = $this;
		$foo = $that;
		$foo->foo = 'bar';
	}

}
