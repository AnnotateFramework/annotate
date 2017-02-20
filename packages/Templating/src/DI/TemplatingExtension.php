<?php

namespace Annotate\Templating\DI;

use Annotate\Templating\TemplateFactory;
use Nette\DI\CompilerExtension;
use Nette\Utils\Validators;


class TemplatingExtension extends CompilerExtension
{

	private $defaults = [
		'directory' => '%appDir%/templates',
	];



	public function loadConfiguration()
	{
		$configuration = $this->getConfig($this->defaults);
		Validators::assertField($configuration, 'directory', 'string');
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('templateFactory'))
			->setClass(TemplateFactory::class, [
				'templatesDirectory' => rtrim($configuration['directory'], '/\\') . DIRECTORY_SEPARATOR
			]);
	}

}
