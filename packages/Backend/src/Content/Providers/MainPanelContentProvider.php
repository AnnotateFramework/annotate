<?php

namespace Annotate\Backend\Content\Providers;

use Nette\Utils\ArrayHash;


class MainPanelContentProvider
{

	const PRIORITY_MIN = -100;

	const PRIORITY_MAX = 100;

	private $links = [];



	public function getLinks()
	{
		$this->sortLinks();

		return ArrayHash::from($this->links);
	}



	private function sortLinks()
	{
		uasort($this->links, function ($a, $b) {
			return $a["priority"] - $b["priority"];
		});
	}



	/**
	 * Adds link to mainPanel
	 *
	 * @param  string
	 * @param  string
	 * @param  string|NULL
	 * @param  int|NULL goes from min to max
	 */
	public function addLink($url, $title, $icon = NULL, $priority = 0)
	{
		$link = [
			"moduleUrl" => str_replace("#", NULL, substr($url, 0, strpos($url, ":"))),
			"url" => $url,
			"title" => $title,
			"icon" => $icon,
			"priority" => $priority
		];
		$this->links[] = $link;
	}

}
