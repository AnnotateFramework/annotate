<?php

namespace Annotate\Diagnostics;

use Nette\Object;
use Tracy\IBarPanel;


class CmsPanel extends Object implements IBarPanel
{

	public static $template = "default";

	public static $pathLen = 30;

	public static $layout = "@layout";

	public static $sections = [];



	/**
	 * Renders HTML code for custom tab.
	 *
	 * @return string
	 */
	public function getTab()
	{
		$html = "<span title='Cms'>Cms</span>";

		return $html;
	}



	/**
	 * Renders HTML code for custom panel.
	 *
	 * @return string
	 */
	public function getPanel()
	{

		$template = "..." . substr(self::$template, -self::$pathLen);
		$layout = "..." . substr(self::$layout, -self::$pathLen);

		$html = "<h1>Cms</h1>";
		$html .= "<div class='nette-inner'>";
		$html .= "<h2>Used template: <br><b title='" . self::$template . "'>" . $template . "</b></h2>";
		$html .= "<h2>Used layout: <br><b title='" . self::$layout . "'>" . $layout . "</b>";

		foreach (self::$sections as $section) {
			if (is_callable($section)) {
				$html .= $section();
			}
		}

		$html .= "</div>";

		return $html;
	}

}
