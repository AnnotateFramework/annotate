<?php

namespace Foo;


class Foo
{

	private $foo;



	public function foo()
	{
		$that = $this;
		$bar = $that;
		$bar->foo = 'bar';
	}

}
