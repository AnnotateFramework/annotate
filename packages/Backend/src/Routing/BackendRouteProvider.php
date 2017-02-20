<?php

namespace Annotate\Backend\Routing;

use Annotate\Framework\Routing\IRouteProvider;
use Nette\Application\IRouter;
use Nette\Application\Routers\Route;


class BackendRouteProvider implements IRouteProvider
{

	/** @var bool */
	private $secured;



	public function __construct($secured)
	{
		$this->secured = $secured;
	}



	public function register(IRouter $router)
	{
		$router[] = new Route(
			"admin/login", [
				"presenter" => "Backend",
				"action" => "login",
			], $this->secured ? Route::SECURED : 0
		);
		$router[] = new Route(
			"admin[/<cmsmodule>[/<action>[/<id>]]]", [
				"presenter" => "Backend",
				"action" => "default",
				"model" => NULL,
				"id" => NULL
			], $this->secured ? Route::SECURED : 0
		);
	}

}
