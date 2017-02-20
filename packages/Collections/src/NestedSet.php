<?php

namespace Annotate\Collections;

use Annotate\Collections\Exceptions\DuplicateItemException;
use ArrayAccess;
use IteratorAggregate;
use Nette\Object;
use Nette\Utils\ArrayHash;


/**
 * @method onAddItem
 */
class NestedSet extends Object implements ArrayAccess, IteratorAggregate
{

	public $onAddItem = [];

	private $type;

	/** @var array|NestedSet[] of items */
	private $items = [];



	public function __construct($type)
	{
		if (!$type) {
			throw new \UnexpectedValueException('$type attribute cannot must be set');
		}
		$this->type = $type;
	}



	public function offsetExists($offset)
	{
		return isset($this->items[$offset]);
	}



	public function offsetGet($offset)
	{
		return $this->items[$offset];
	}



	public function offsetSet($offset, $value)
	{
		$this->addItem($offset, $value);
	}



	public function offsetUnset($offset)
	{
		unset($this->items[$offset]);
	}



	/**
	 * @param $key
	 * @param $item
	 *
	 * @return NestedSet|mixed
	 * @throws Exceptions\DuplicateItemException
	 * @throws \UnexpectedValueException
	 */
	public function addItem($key, $item)
	{
		if (is_object($item)) {
			if (get_class($item) !== $this->type) {
				$type = get_class($item);
				throw new \UnexpectedValueException(
					"Cannot add item. Expected instance of '{$this->type}' got instance of '{$type}'"
				);
			}
		} elseif (gettype($item) != $this->type) {
			$type = gettype($item);
			if ($this->type == Type::TYPE_ARRAY_HASH && $type == Type::TYPE_ARRAY) {
				$item = ArrayHash::from($item);
			} else {
				throw new \UnexpectedValueException(
					"Cannot add item. Expected type '{$this->type}' got item of type '{$type}'"
				);
			}
		}

		if (in_array($item, $this->items)) {
			throw new DuplicateItemException("Cannot add item. Same item already exist in set");
		}

		$this->onAddItem($item, $this);

		$this->items[$key] = $item;

		return $item;
	}



	public function getIterator()
	{
		return new \RecursiveArrayIterator($this->items);
	}



	public function getItems()
	{
		return $this->items;
	}

}
