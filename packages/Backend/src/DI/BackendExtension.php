<?php

namespace Annotate\Backend\DI;

use Annotate\Backend\Content\Providers\MainPanelContentProvider;
use Annotate\Backend\Content\Providers\ModulesNavigationContentProvider;
use Annotate\Backend\Modules\BackendModulesFactory;
use Annotate\Backend\Routing\BackendRouteProvider;
use Annotate\Backend\UI\Components\Factories\IMainPanelFactory;
use Annotate\Backend\UI\Components\Factories\IModulesNavigationFactory;
use Annotate\Modules\DI\ModulesExtension;
use Nette\DI\CompilerExtension;


class BackendExtension extends CompilerExtension
{

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('routeProvider'))
			->setClass(BackendRouteProvider::class, [
				'secured' => isset($builder->parameters['https']) ? $builder->parameters['https'] : FALSE,
			]);

		$builder->addDefinition($this->prefix('modulesFactory'))
			->setClass(BackendModulesFactory::class)
			->addTag(ModulesExtension::TAG_MODULES_FACTORY);

		$builder->addDefinition($this->prefix('mainPanel.content'))
			->setClass(MainPanelContentProvider::class);

		$builder->addDefinition($this->prefix('modulesNavigation.content'))
			->setClass(ModulesNavigationContentProvider::class);

		$builder->addDefinition($this->prefix('mainPanel.factory'))
			->setImplement(IMainPanelFactory::class);

		$builder->addDefinition($this->prefix('modulesNavigation.factory'))
			->setImplement(IModulesNavigationFactory::class);
	}

}
