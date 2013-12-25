<?php

namespace RdnUpload\Object;

use DateTime;

/**
 * Local filesystem uploaded object.
 */
class Local implements ObjectInterface
{
	/**
	 * @var string
	 */
	protected $path;

	/**
	 * @var string
	 */
	protected $publicUrl;

	/**
	 * @param string $path The upload path to the file.
	 * @param string $publicUrl The public path to the file.
	 */
	public function __construct($path, $publicUrl)
	{
		$this->path = $path;
		$this->publicUrl = $publicUrl;
	}

	public function getPath()
	{
		return $this->path;
	}

	public function getPublicUrl()
	{
		return $this->publicUrl;
	}

	public function getContent()
	{
		ob_start();
		readfile($this->path);
		return ob_get_clean();
	}

	public function getBasename()
	{
		return pathinfo($this->path, PATHINFO_BASENAME);
	}

	public function getExtension()
	{
		return pathinfo($this->path, PATHINFO_EXTENSION);
	}

	public function getContentLength()
	{
		return filesize($this->path);
	}

	public function getLastModified()
	{
		$time = new DateTime;
		$time->setTimestamp(filemtime($this->path));
		return $time;
	}

	public function __toString()
	{
		return $this->publicUrl;
	}
}
