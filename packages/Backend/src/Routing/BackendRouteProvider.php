<?php

namespace Annotate\Backend\Routing;

use Annotate\Framework\Routing\IRouteProvider;
use Nette\Application\IRouter;
use Nette\Application\Routers\Route;


class BackendRouteProvider implements IRouteProvider
{

	public function register(IRouter $router)
	{
		$router[] = new Route(
			"admin/login", [
				"presenter" => "Backend",
				"action" => "login",
			]
		);
		$router[] = new Route(
			"admin[/<cmsmodule>[/<action>[/<id>]]]", [
				"presenter" => "Backend",
				"action" => "default",
				"model" => NULL,
				"id" => NULL
			]
		);
	}

}
