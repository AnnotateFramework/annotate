<?php

namespace Annotate\Templating;

use Nette\Application\UI\Presenter;
use Nette\Bridges\ApplicationLatte\Template;


interface ITemplateFactory
{

	const INTERFACENAME = __CLASS__;



	function formatTemplateFiles($templateFile, Presenter $presenter);



	function addTemplate($path);



	function replaceLayout($key, $path);



	function formatComponentTemplateFiles(Template $template, $templateName, $localPath);



	function replaceTemplate($key, $path);



	function formatLayoutTemplateFiles($layout, Presenter $presenter);



	function addLayout($path);



	function setupTemplate(Template $template);



	function createFormTemplate($templateName, $localPath);

}
