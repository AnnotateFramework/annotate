<?php

namespace Annotate\Backend\Modules;

use Annotate\Backend\Content\Providers\MainPanelContentProvider;
use Annotate\Modules\ModuleDefinition;
use Annotate\Modules\ModuleFactory;


class BackendModulesFactory extends ModuleFactory
{

	/** @var MainPanelContentProvider */
	private $mainPanel;



	public function __construct(MainPanelContentProvider $mainPanel)
	{
		$this->mainPanel = $mainPanel;
	}



	public function provides()
	{
		return [
			new ModuleDefinition(DashboardModule::class, 'dashboard', 'glyphicon glyphicon-home', ModuleDefinition::TYPE_HIDDEN),
		];
	}



	public function register()
	{
		$this->mainPanel->addLink(
			"#dashboard:default",
			"Nástěnka",
			"glyphicon glyphicon-home",
			MainPanelContentProvider::PRIORITY_MIN
		);
	}

}
