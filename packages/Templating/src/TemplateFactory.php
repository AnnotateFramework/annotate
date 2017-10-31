<?php

namespace Annotate\Templating;

use Annotate\Diagnostics\CmsPanel;
use Nette\Application\UI\Presenter;
use Nette\Bridges\ApplicationLatte\Template;
use Latte\Engine;
use Nette\Object;
use Nette\Templating\FileTemplate;


/**
 * @method onLoadTemplate
 * @method onLoadComponentTemplate
 * @method onLoadLayout
 * @method onSetupTemplate
 * @method onCreateFormTemplate
 */
class TemplateFactory extends Object implements ITemplateFactory
{

	public $onLoadTemplate = [];

	public $onLoadLayout = [];

	public $onLoadComponentTemplate = [];

	public $onSetupTemplate = [];

	public $onCreateFormTemplate = [];

	private $templates = [];

	private $layouts = [];

	private $templatesDirectory;



	public function __construct($templatesDirectory)
	{
		$this->templatesDirectory = $templatesDirectory;
	}



	public function formatTemplateFiles($templateFile, Presenter $presenter)
	{
		$this->onLoadTemplate($this, $templateFile, $presenter->getName());
		$this->templates[] = $this->templatesDirectory . $presenter->getName() . DIRECTORY_SEPARATOR . $templateFile . ".latte";
		$this->templates[] = $this->templatesDirectory . "$templateFile.latte";

		foreach ($this->templates as $t) {
			if (file_exists($t)) {
				CmsPanel::$template = $t;
				break;
			}
		}

		return $this->templates;
	}



	public function formatLayoutTemplateFiles($layout, Presenter $presenter)
	{
		$layout = "@" . $layout;
		$this->onLoadLayout($this, $layout, $presenter->getName());
		$this->layouts[] = $this->templatesDirectory . $presenter->getName() . DIRECTORY_SEPARATOR . $layout . ".latte";
		$this->layouts[] = $this->templatesDirectory . "$layout.latte";

		foreach ($this->layouts as $l) {
			if (file_exists($l)) {
				CmsPanel::$layout = $l;
				break;
			}
		}

		return $this->layouts;
	}



	public function formatComponentTemplateFiles(Template $template, $templateName, $localPath)
	{
		$this->setupTemplate($template);
		if ($templateName == "") {
			return;
		}
		if (file_exists($localPath)) {
			$template->setFile($localPath);
		}
		$this->onLoadComponentTemplate($template, $templateName);
	}



	public function setupTemplate(Template $template)
	{
		$this->onSetupTemplate($template);
	}



	public function replaceTemplate($key, $path)
	{
		$this->templates[$key] = $path;
	}



	public function replaceLayout($key, $path)
	{
		$this->layouts[$key] = $path;
	}



	public function addTemplate($path)
	{
		$this->templates[] = $path;
	}



	public function addLayout($path)
	{
		$this->layouts[] = $path;
	}



	public function createFormTemplate($templateName, $localPath)
	{
		$template = $this->createTemplate();
		if (file_exists($localPath)) {
			$template->setFile($localPath);
		}
		$this->onCreateFormTemplate($templateName, $template);

		return $template;
	}



	public function createTemplate()
	{
		$template = new FileTemplate();
		$template->registerFilter(new Engine());
		$template->registerHelperLoader('Nette\\Templating\\Helpers::loader');

		return $template;
	}

}
