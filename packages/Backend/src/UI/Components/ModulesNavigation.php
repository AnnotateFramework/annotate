<?php

namespace Annotate\Backend\UI\Components;

use Annotate\Backend\Content\Providers\ModulesNavigationContentProvider;
use Annotate\Framework\Application\Components\BaseComponent;


class ModulesNavigation extends BaseComponent
{

	/** @var ModulesNavigationContentProvider */
	private $modulesNavigation;



	public function __construct(ModulesNavigationContentProvider $modulesNavigation)
	{
		$this->modulesNavigation = $modulesNavigation;
	}



	public function build($args = [])
	{
		$this->template->links = $this->modulesNavigation->getLinks();
	}

}
