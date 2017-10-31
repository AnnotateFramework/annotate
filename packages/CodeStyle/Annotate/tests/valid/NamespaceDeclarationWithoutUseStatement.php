<?php

namespace Foo;


class Bar
{

	public function foo()
	{
		$bar = NULL;
		return function () use ($bar) {};
	}

}
