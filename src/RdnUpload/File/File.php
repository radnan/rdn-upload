<?php

namespace RdnUpload\File;

/**
 * Generic local file object.
 */
class File implements FileInterface
{
	/**
	 * @var string
	 */
	protected $basename;

	/**
	 * @var string
	 */
	protected $path;

	/**
	 * @param string $basename
	 * @param string $path
	 *
	 * @throws \RuntimeException
	 */
	public function __construct($basename, $path)
	{
		$this->basename = $basename;
		$this->path = $path;
	}

	public function getBasename()
	{
		return $this->basename;
	}

	public function getFilename()
	{
		return pathinfo($this->basename, PATHINFO_FILENAME);
	}

	public function getExtension()
	{
		return pathinfo($this->basename, PATHINFO_EXTENSION);
	}

	public function getPath()
	{
		return $this->path;
	}
}
