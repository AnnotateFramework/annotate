<?php

namespace Annotate\Backend\UI;

use Annotate\Collections\NestedSet;
use Annotate\Framework\Utils\Strings;


class Link extends NestedSet
{

	private $rawUrl;

	private $url;

	private $icon;

	private $text;

	private $attributes = [];



	public function __construct($url, $text, $icon = NULL)
	{
		$this->rawUrl = str_replace(' ', NULL, Strings::fromDashes($url));
		$this->url = $url;
		$this->icon = $icon;
		$this->text = $text;
		parent::__construct(self::class);
	}



	/**
	 * @return mixed
	 */
	public function getText()
	{
		return $this->text;
	}



	/**
	 * @return mixed
	 */
	public function getIcon()
	{
		return $this->icon;
	}



	/**
	 * @return mixed
	 */
	public function getUrl()
	{
		return $this->url;
	}



	public function hasChildren()
	{
		return count($this->getItems());
	}



	/**
	 * @return mixed|string
	 */
	public function getRawUrl()
	{
		return $this->rawUrl;
	}



	/**
	 * @return array
	 */
	public function getAttributes()
	{
		return $this->attributes;
	}



	public function setAttribute($attribute, $value)
	{
		$this->attributes[$attribute] = $value;
	}

}
