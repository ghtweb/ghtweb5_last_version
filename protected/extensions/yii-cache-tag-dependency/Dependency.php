<?php

namespace TaggedCache;

/**
 * Class Dependency
 * @package TaggedCache
 */
class Dependency implements \ICacheDependency
{
	/**
	 * List of tags
	 * Array of string or Tag[]
	 * @var string[]|Tag[]|null
	 */
	public $tags = null;

	/**
	 * List of tags versions
	 * @var null
	 */
	public $versions = null;

	function __construct(array $tags)
	{
		$this->tags = array();
		foreach ($tags as $tag)
		{
			$this->tags[] = ($tag instanceof Tag) ? $tag->getTag() : $tag;
		}
	}

	/**
	 * Evaluates the dependency by generating and saving the data related with dependency.
	 * This method is invoked by cache before writing data into it.
	 */
	public function evaluateDependency()
	{
		if ($this->tags === null || !is_array($this->tags))
		{
			return;
		}

		$tagsWithoutVersion = $tagsWithVersion = $tags = array();
		foreach ($this->tags as $tag)
		{
			if (!($tag instanceof Tag))
			{
				$tag = new Tag($tag);
			}
			$tagKey = $tag->getTag();
			$tags[$tagKey] = $tag;
			if (false === ($version = $tag->get()))
			{
				$tagsWithoutVersion[] = $tagKey;
			} else
			{
				$tagsWithVersion[$tagKey] = $version;
			}
		}

		// load unknown tags from cache
		$versions = !empty($tagsWithoutVersion) ? \Yii::app()->cache->mget($tagsWithoutVersion) : array();
		foreach ($versions as $tagKey => $version)
		{
			// merge known and loaded tags
			$tagsWithVersion[$tagKey] = $version;
		}

		$this->versions = $tagsWithVersion;
		foreach ($tags as $tag => $tagInst)
		{
			if (!isset($this->versions[$tag]) || false === $this->versions[$tag])
			{
				// generate tags that are not in cache
				$this->versions[$tag] = $version = $this->getNewTagVersion();
				$tagInst->set($version)->save();
			}
		}
		ksort($this->versions);
	}

	/**
	 * @return boolean whether the dependency has changed.
	 */
	public function getHasChanged()
	{
		if ($this->versions === null || !is_array($this->versions))
		{
			return true;
		}

		$tagsWithoutVersion = $tagsWithVersion = $tags = array();
		foreach ($this->tags as $tag)
		{
			if (!($tag instanceof Tag))
			{
				$tag = new Tag($tag);
			}
			$tags[$tag->getTag()] = $tag;
			if (false === ($version = $tag->get()))
			{
				$tagsWithoutVersion[] = $tag->getTag();
			} else
			{
				$tagsWithVersion[$tag->getTag()] = $version;
			}
		}

		$versions = !empty($tagsWithoutVersion) ? \Yii::app()->cache->mget($tagsWithoutVersion) : array();
		foreach ($versions as $tag => $version)
		{
			$tagsWithVersion[$tag] = $version;
			$tags[$tag]->set($version);
		}

		foreach ($this->versions as $tag => $savedTagVersion)
		{
			// if is absent in cache or saved versions differs from current version
			if (!isset($tagsWithVersion[$tag]) || $savedTagVersion != $tagsWithVersion[$tag])
			{
				return true;
			}
		}
		return false;
	}

	/**
	 * Returns new unique version string
	 * @return string
	 */
	protected function getNewTagVersion()
	{
		static $counter = 0;
		$counter++;
		return md5(microtime() . getmypid() . uniqid('')) . '_' . $counter;
	}
}