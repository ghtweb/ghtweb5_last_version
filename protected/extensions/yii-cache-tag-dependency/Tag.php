<?php

namespace TaggedCache;

/**
 * Class Tag
 * @package TaggedCache
 */
class Tag
{
	private $_tag;

	private static $_values = array();

	public function __construct($tag = null)
	{
		$this->setTag($tag);
	}

	/**
	 * Set name of tag
	 * @param mixed $this
	 */
	public function setTag($tag = null)
	{
		if (empty($tag))
		{
			$tag = '@' . get_class($this);
		}
		if ('@' !== $tag{0})
		{
			$tag = '@' . get_class($this) . '::' . $tag;
		}
		$this->_tag = $tag;
		return $this;
	}

	/**
	 * Return name of tag
	 * @return mixed
	 */
	public function getTag()
	{
		return $this->_tag;
	}

	/**
	 * Return current version of tag
	 * @return mixed
	 */
	public function get()
	{
		if (!isset(self::$_values[$this->getTag()]))
			return false;

		return self::$_values[$this->getTag()];
	}

	/**
	 * Set current tag version
	 * @param mixed $value
	 * @return $this
	 */
	public function set($value)
	{
		self::$_values[$this->getTag()] = $value;
		return $this;
	}

	/**
	 * Save current tag version into cache
	 * @return $this
	 */
	public function save()
	{
		if (false !== ($value = $this->get()))
			\Yii::app()->cache->set($this->getTag(), $value, /* forever */ 0);
		return $this;
	}

	/**
	 * Remove tag value from cache
	 * @return $this
	 */
	public function delete()
	{
		unset(self::$_values[$this->getTag()]);
		\Yii::app()->cache->delete($this->getTag());
		return $this;
	}
}