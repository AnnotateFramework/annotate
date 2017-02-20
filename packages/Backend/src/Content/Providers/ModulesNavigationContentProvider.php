<?php

namespace Annotate\Backend\Content\Providers;

use Annotate\Backend\UI\Link;
use Annotate\Collections\NestedSet;
use Annotate\Modules\ModuleLinksExtractor;
use Annotate\Modules\ModulesRegister;


class ModulesNavigationContentProvider
{

	/** @var ModulesRegister */
	private $modulesRegister;

	/** @var NestedSet */
	private $links;

	/** @var ModuleLinksExtractor */
	private $moduleLinksExtractor;



	public function __construct(ModulesRegister $modulesRegister, ModuleLinksExtractor $moduleLinksExtractor)
	{
		$this->modulesRegister = $modulesRegister;
		$this->moduleLinksExtractor = $moduleLinksExtractor;
	}



	public function getLinks()
	{
		$this->links = new NestedSet(Link::class);

		$definitions = $this->modulesRegister->getDefinitions();
		foreach ($definitions as $definition) {

			if ($definition->getType() == $definition::TYPE_HIDDEN) {
				continue;
			}

			$node = $this->links->addItem(
				$definition->getUrl(),
				new Link("#" . $definition->getUrl(), 'modules.' . $definition->getUrl() . '.name', $definition->getIcon())
			);

			$links = $this->moduleLinksExtractor->extractForModule($definition->getUrl());
			foreach ($links as $key => $link) {
				$node->addItem($key, $link);
			}
		}

		return $this->links;
	}

}
