<?php

namespace Annotate\Backend\UI\Components;

use Annotate\Backend\Content\Providers\MainPanelContentProvider;
use Annotate\Framework\Application\Components\BaseComponent;


class MainPanel extends BaseComponent
{

	/** @var MainPanelContentProvider */
	private $mainPanelService;



	public function __construct(MainPanelContentProvider $mainPanel)
	{
		$this->mainPanelService = $mainPanel;
	}



	public function build($args = [])
	{
		$this->template->links = $this->mainPanelService->getLinks();
	}

}
